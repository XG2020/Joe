// 获取API配置 - 优先使用PHP传入的配置，其次使用默认值
function getApiConfig() {
    // 优先使用PHP传入的配置（通过全局变量）- 检查是否为空
    if (typeof window.apiId !== 'undefined' && typeof window.apiKey !== 'undefined' && 
        window.apiId && window.apiKey) {
        return {
            API_ID: window.apiId,
            API_KEY: window.apiKey
        };
    }
    
    // 其次使用script标签中定义的变量 - 检查是否为空
    if (typeof apiId !== 'undefined' && typeof apiKey !== 'undefined' && 
        apiId && apiKey) {
        return {
            API_ID: apiId,
            API_KEY: apiKey
        };
    }
    
    // 最后使用默认值（仅用于开发测试）
    console.warn('未检测到有效的API配置，使用默认值');
    return {
        API_ID: '88888888',
        API_KEY: '88888888'
    };
}

class WeatherWidget {
    constructor(config = {}) {
        this.apiUrl = 'https://cn.apihz.cn/api/tianqi/tqybip.php';
        
        const apiConfig = getApiConfig();
        this.apiId = config.apiId || apiConfig.API_ID;
        this.apiKey = config.apiKey || apiConfig.API_KEY;
        
        // 保存配置到全局变量，供getVisitorIp函数使用
        if (typeof window !== 'undefined') {
            window.apiId = this.apiId;
            window.apiKey = this.apiKey;
        }
        
        this.ip = null; // 初始化为null，将在loadWeather中异步获取
        this.weatherData = null;
        this.container = null;
        
        this.init();
    }

    // 中国省份数据（仅省份名称，城市数据通过API获取）
    provinceCityData = {
        '北京': [],
        '天津': [],
        '河北': [],
        '山西': [],
        '内蒙古': [],
        '辽宁': [],
        '吉林': [],
        '黑龙江': [],
        '上海': [],
        '江苏': [],
        '浙江': [],
        '安徽': [],
        '福建': [],
        '江西': [],
        '山东': [],
        '河南': [],
        '湖北': [],
        '湖南': [],
        '广东': [],
        '广西': [],
        '海南': [],
        '重庆': [],
        '四川': [],
        '贵州': [],
        '云南': [],
        '西藏': [],
        '陕西': [],
        '甘肃': [],
        '青海': [],
        '宁夏': [],
        '新疆': [],
        '台湾': [],
        '香港': [],
        '澳门': []
    }

    init() {
        this.createWidget();
        // 无 .xweather 容器（如移动端隐藏侧边栏）时直接退出，避免后续 querySelector 报错
        if (!this.container) {
            return;
        }
        this.initProvinceSelector();
        this.loadWeather();
        
        // 每30分钟自动刷新一次
        setInterval(() => {
            this.loadWeather();
        }, 30 * 60 * 1000);
    }

    createWidget() {
        // 获取目标容器
        this.container = document.querySelector('.xweather');
        if (!this.container) {
            console.error('未找到class为xweather的容器');
            return;
        }

        // 创建挂件HTML结构
        this.container.innerHTML = `
            <div class="weather-widget">
                <div class="loading" id="loading">正在加载天气数据...</div>
                <div class="error hidden" id="err"></div>
                
                <div class="weather-container hidden" id="weather-container">
                    <div class="alarm-info hidden" id="alarm-info">
                        <div class="alarm-marquee" id="alarm-marquee"></div>
                    </div>

                    <div class="current-weather">
                        <div class="location">
                            <div class="city-dropdown-container">
                                <span id="city" class="city-display">--</span>
                                <div class="city-dropdown hidden">
                                    <select id="province-select" class="province-select">
                                        <option value="">请选择省份</option>
                                    </select>
                                    <select id="city-select" class="city-select" disabled>
                                        <option value="">请选择城市</option>
                                    </select>
                                </div>
                            </div>
                            <span id="update-time" class="update-time"></span>
                        </div>
                        <div class="current-main">
                            <div class="current-temp">
                                <span id="current-temp">--</span>°C
                            </div>
                            <div class="current-info">
                                <div class="weather-icon">
                                    <img id="current-icon" src="" alt="天气图标">
                                </div>
                                <div class="weather-desc">
                                    <div id="current-weather">--</div>
                                    <div class="weather-details">
                                        <span id="current-wind">--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="current-humidity-main">
                            湿度: <span id="current-humidity-main">--</span>%
                        </div>
                    </div>

                    <div class="forecast-content">
                        <div class="hourly-forecast" id="hourly-forecast">
                            <div class="hourly-list" id="hourly-list">
                                <!-- 小时预报将在这里动态生成 -->
                            </div>
                        </div>
                        <!-- 分页指示器 -->
                        <div class="pagination-dots" id="pagination-dots">
                            <span class="dot active" data-page="0"></span>
                            <span class="dot" data-page="1"></span>
                            <span class="dot" data-page="2"></span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    async loadWeather() {
        try {
            this.showLoading();
            
            // 如果IP地址还未获取，先获取IP地址
            if (!this.ip) {
                this.ip = await getVisitorIp();
            }
            
            // 如果仍然无法获取IP，使用默认IP
            const ipToUse = this.ip || '210.51.167.169';
            //console.log('使用的IP地址:', ipToUse);
            
            const params = new URLSearchParams({
                id: this.apiId,
                key: this.apiKey,
                ip: ipToUse, // 使用获取到的IP地址
                day: 3, // 获取天数据
                hourtype: 1 // 返回时段天气预报
            });

            const response = await fetch(`${this.apiUrl}?${params}`);
            const data = await response.json();

            if (data.code === 200) {
                this.weatherData = data;
                this.renderWeather();
                this.hideLoading();
            } else {
                this.showError(data.msg || '获取天气数据失败');
            }
        } catch (error1) {
            //console.error('加载天气数据失败:', error);
            this.showError('网络错误，请检查网络连接');
        }
    }

    renderWeather() {
        if (!this.weatherData) return;

        this.renderCurrentWeather();
        this.renderHourlyForecast(1); // 只显示今天的2小时预报
        this.renderAlarm();
    }

    renderCurrentWeather() {
        if (!this.container) return;
        
        const data = this.weatherData;
        const nowInfo = data.nowinfo;

        // 基本信息 - 只显示市，不显示省份
        const selectedCity = this.container.querySelector('#city-select').value;
        const cityDisplay = this.container.querySelector('#city');
        
        if (selectedCity) {
            cityDisplay.textContent = selectedCity;
        } else {
            cityDisplay.textContent = data.name;
            cityDisplay.title = `自动定位: ${data.name} (点击可手动选择城市)`;
        }
        
        // 格式化更新时间，去掉分和秒，只显示年月日时
        const updateTime = data.uptime;
        const timeParts = updateTime.split(' ');
        if (timeParts.length >= 2) {
            const datePart = timeParts[0]; // 年月日
            const timePart = timeParts[1]; // 时分秒
            const hour = timePart.split(':')[0]; // 只取小时
            this.container.querySelector('#update-time').textContent = `更新于\n${datePart} ${hour}时`;
        } else {
            this.container.querySelector('#update-time').textContent = `更新于\n${updateTime}`;
        }
        
        // 当前温度
        this.container.querySelector('#current-temp').textContent = nowInfo.temperature;
        
        // 当前天气
        this.container.querySelector('#current-weather').textContent = data.weather1;
        this.container.querySelector('#current-wind').textContent = `${nowInfo.windDirection} ${nowInfo.windScale}`;
        this.container.querySelector('#current-humidity-main').textContent = nowInfo.humidity;
        
        // 天气图标
        const currentIcon = this.getWeatherIcon(data.weather1img);
        this.container.querySelector('#current-icon').src = currentIcon;
        this.container.querySelector('#current-icon').alt = data.weather1;
    }

    // 初始化省份选择器
    initProvinceSelector() {
        const provinceSelect = this.container.querySelector('#province-select');
        const citySelect = this.container.querySelector('#city-select');
        const cityDisplay = this.container.querySelector('#city');
        const cityDropdown = this.container.querySelector('.city-dropdown');
        
        if (!provinceSelect || !citySelect || !cityDisplay || !cityDropdown) return;
        
        // 添加重置按钮到城市选择器
        const resetButton = document.createElement('button');
        resetButton.className = 'reset-city-btn';
        resetButton.textContent = '自动定位';
        resetButton.title = '点击返回基于IP的自动定位';
        resetButton.style.cssText = `
            margin-top: 8px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            color: white;
            font-size: 11px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        `;
        
        // 重置按钮事件
        resetButton.addEventListener('click', () => {
            this.resetCitySelector();
        });
        
        // 添加悬停效果
        resetButton.addEventListener('mouseenter', () => {
            resetButton.style.background = 'rgba(255, 255, 255, 0.3)';
        });
        
        resetButton.addEventListener('mouseleave', () => {
            resetButton.style.background = 'rgba(255, 255, 255, 0.2)';
        });
        
        cityDropdown.appendChild(resetButton);
        
        // 填充省份选项
        const provinces = Object.keys(this.provinceCityData);
        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            provinceSelect.appendChild(option);
        });
        
        // 省份选择事件
        provinceSelect.addEventListener('change', async (e) => {
            const selectedProvince = e.target.value;
            await this.updateCityOptions(selectedProvince);
        });
        
        // 城市选择事件
        citySelect.addEventListener('change', (e) => {
            const selectedCity = e.target.value;
            if (selectedCity) {
                // 获取选中的城市文本
                const selectedOption = e.target.options[e.target.selectedIndex];
                const cityDisplayName = selectedOption.textContent;
                this.loadWeatherByCity(selectedCity);
                cityDropdown.classList.add('hidden');
            }
        });
        
        // 城市显示点击事件
        cityDisplay.style.cursor = 'pointer';
        cityDisplay.title = '点击选择城市';
        cityDisplay.addEventListener('click', (e) => {
            e.stopPropagation();
            cityDropdown.classList.toggle('hidden');
        });
        
        // 点击其他地方关闭下拉菜单
        document.addEventListener('click', (e) => {
            if (!cityDropdown.contains(e.target) && e.target !== cityDisplay) {
                cityDropdown.classList.add('hidden');
            }
        });
        
        // 阻止下拉菜单内部点击事件冒泡
        cityDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
    
    // 重置城市选择器
    resetCitySelector() {
        const provinceSelect = this.container.querySelector('#province-select');
        const citySelect = this.container.querySelector('#city-select');
        
        // 重置选择器
        provinceSelect.value = '';
        citySelect.value = '';
        citySelect.innerHTML = '<option value="">请选择城市</option>';
        citySelect.disabled = true;
        
        // 隐藏下拉菜单
        this.container.querySelector('.city-dropdown').classList.add('hidden');
        
        // 重新加载天气数据
        this.loadWeather();
    }
    
// 获取城市列表数据
    async getCityList(province) {
        try {
            const params = new URLSearchParams({
                id: this.apiId,
                key: this.apiKey,
                sheng: province
            });
            
            const response = await fetch(`https://cn.apihz.cn/api/tianqi/tqyblist.php?${params}`);
            const data = await response.json();
            
            if (data.code === 200 && data.list) {
                return data.list.map(item => ({
                    shi: item.shi,
                    name: item.name
                }));
            } else {
                console.warn('获取城市列表失败:', data.msg);
                return [];
            }
        } catch (error) {
            console.error('获取城市列表失败:', error);
            return [];
        }
    }
    
    // 更新城市选项
    async updateCityOptions(province) {
        const citySelect = this.container.querySelector('#city-select');
        const cityDropdown = this.container.querySelector('.city-dropdown');
        
        if (!province) {
            citySelect.innerHTML = '<option value="">请选择城市</option>';
            citySelect.disabled = true;
            return;
        }
        
        // 显示加载状态
        citySelect.innerHTML = '<option value="">正在加载城市列表...</option>';
        citySelect.disabled = true;
        
        // 获取城市列表
        const cities = await this.getCityList(province);
        
        if (cities.length === 0) {
            // 如果接口获取失败，显示错误信息
            citySelect.innerHTML = '<option value="">获取城市列表失败</option>';
            console.warn('获取城市列表失败，请检查网络连接或API配置');
        } else {
            // 使用接口返回的城市数据
            citySelect.innerHTML = '<option value="">请选择城市</option>';
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.shi; 
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
        }
        
        citySelect.disabled = false;
        
        // 保持下拉菜单显示
        if (!cityDropdown.classList.contains('hidden')) {
            cityDropdown.classList.remove('hidden');
        }
    }
    
    // 根据城市加载天气
    async loadWeatherByCity(cityValue) {
        try {
            this.showLoading();
            
            // 获取省份和城市信息
            const provinceSelect = this.container.querySelector('#province-select');
            const citySelect = this.container.querySelector('#city-select');
            const selectedProvince = provinceSelect.value;
            
            if (!selectedProvince || !cityValue) {
                this.showError('请选择省份和城市');
                return;
            }
            
            // 获取选中的城市显示名称
            const selectedOption = citySelect.options[citySelect.selectedIndex];
            const cityDisplayName = selectedOption ? selectedOption.textContent : cityValue;
            
            
            // 更新城市显示为纯城市名称，并添加选择模式标识
            const cityDisplay = this.container.querySelector('#city');
            cityDisplay.textContent = cityDisplayName;
            cityDisplay.title = `手动选择: ${selectedProvince} - ${cityDisplayName} (点击可重新选择)`;
            
            // 隐藏下拉菜单
            this.container.querySelector('.city-dropdown').classList.add('hidden');
            
            // 使用新的API地址格式查询城市天气
            const params = new URLSearchParams({
                id: this.apiId,
                key: this.apiKey,
                sheng: selectedProvince,
                place: cityValue,
                day: 3,
                hourtype: 1
            });
            const cityApiUrl = 'https://cn.apihz.cn/api/tianqi/tqyb.php';
            const response = await fetch(`${cityApiUrl}?${params}`);
            const data = await response.json();
            
            if (data.code === 200) {
                this.weatherData = data;
                this.renderWeather();
                this.hideLoading();
            } else {
                this.showError(data.msg || '获取天气数据失败');
            }
        } catch (error) {
            console.error('加载城市天气数据失败:', error);
            this.showError('网络错误，请检查网络连接');
        }
    }



    renderHourlyForecast(day) {
        if (!this.container) return;
        
        const hourlyData = this.getHourlyData(day);
        const hourlyForecast = this.container.querySelector('#hourly-forecast');
        
        if (!hourlyData || hourlyData.length === 0) {
            hourlyForecast.classList.add('hidden');
            hourlyForecast.classList.remove('visible');
            return;
        }

        hourlyForecast.classList.remove('hidden');
        hourlyForecast.classList.add('visible');
        
        // 获取所有预报数据
        const currentHour = new Date().getHours();
        const allForecastItems = [];
        
        // 生成12个2小时间隔的预报
        for (let i = 0; i < 12; i++) {
            const targetHour = (currentHour + (i * 2)) % 24;
            const dayOffset = Math.floor((currentHour + (i * 2)) / 24);
            
            if (dayOffset === 0) {
                // 今天的预报
                const hourIndex = Math.floor(targetHour / 3);
                if (hourlyData[hourIndex]) {
                    allForecastItems.push({
                        time: `${targetHour.toString().padStart(2, '0')}:00`,
                        weather: hourlyData[hourIndex]['天气'],
                        temp: hourlyData[hourIndex]['气温'],
                        icon: this.getWeatherIcon(hourlyData[hourIndex]['图标'])
                    });
                }
            } else if (dayOffset === 1) {
                // 明天的预报
                const tomorrowHourlyData = this.getHourlyData(2);
                if (tomorrowHourlyData && tomorrowHourlyData.length > 0) {
                    const tomorrowHourIndex = Math.floor(targetHour / 3);
                    if (tomorrowHourlyData[tomorrowHourIndex]) {
                        allForecastItems.push({
                            time: `明${targetHour.toString().padStart(2, '0')}:00`,
                            weather: tomorrowHourlyData[tomorrowHourIndex]['天气'],
                            temp: tomorrowHourlyData[tomorrowHourIndex]['气温'],
                            icon: this.getWeatherIcon(tomorrowHourlyData[tomorrowHourIndex]['图标'])
                        });
                    }
                }
            } else {
                // 后天的预报（第三页）
                const dayAfterTomorrowData = this.getHourlyData(3);
                if (dayAfterTomorrowData && dayAfterTomorrowData.length > 0) {
                    const dayAfterHourIndex = Math.floor(targetHour / 3);
                    if (dayAfterTomorrowData[dayAfterHourIndex]) {
                        allForecastItems.push({
                            time: `后${targetHour.toString().padStart(2, '0')}:00`,
                            weather: dayAfterTomorrowData[dayAfterHourIndex]['天气'],
                            temp: dayAfterTomorrowData[dayAfterHourIndex]['气温'],
                            icon: this.getWeatherIcon(dayAfterTomorrowData[dayAfterHourIndex]['图标'])
                        });
                    }
                }
            }
        }

        // 存储预报数据并初始化分页
        this.hourlyForecastData = allForecastItems;
        this.currentPage = 0;
        this.totalPages = Math.ceil(allForecastItems.length / 4);
        
        // 渲染第一页
        this.renderHourlyPage(0);
        
        // 绑定分页事件
        this.bindPaginationEvents();
    }

    renderHourlyPage(pageIndex) {
        if (!this.hourlyForecastData || !this.container) return;
        
        const hourlyList = this.container.querySelector('#hourly-list');
        hourlyList.innerHTML = '';
        
        // 计算当前页的数据范围
        const startIndex = pageIndex * 4;
        const endIndex = Math.min(startIndex + 4, this.hourlyForecastData.length);
        const pageItems = this.hourlyForecastData.slice(startIndex, endIndex);
        
        // 渲染当前页的预报项
        pageItems.forEach(item => {
            const hourItem = document.createElement('div');
            hourItem.className = 'hourly-item';
            
            hourItem.innerHTML = `
                <div class="time">${item.time}</div>
                <div class="icon"><img src="${item.icon}" alt="${item.weather}"></div>
                <div class="temp">${item.temp}</div>
            `;
            
            hourlyList.appendChild(hourItem);
        });
        
        // 更新分页指示器
        this.updatePaginationDots(pageIndex);
    }

    bindPaginationEvents() {
        if (!this.container) return;
        
        const dots = this.container.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                this.currentPage = index;
                this.renderHourlyPage(index);
            });
        });
    }

    updatePaginationDots(activeIndex) {
        if (!this.container) return;
        
        const dots = this.container.querySelectorAll('.dot');
        dots.forEach((dot, index) => {
            if (index === activeIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    renderAlarm() {
        if (!this.container) return;
        
        const alarmData = this.weatherData.alarm;
        const alarmInfo = this.container.querySelector('#alarm-info');
        const alarmMarquee = this.container.querySelector('#alarm-marquee');
        const weatherContainer = this.container.querySelector('.weather-widget');

        if (alarmData && alarmData.title) {
            // 显示预警信息
            alarmInfo.classList.remove('hidden');
            alarmInfo.classList.add('visible');
            
            // 构建跑马灯内容
            const alarmText = `⚠️ ${alarmData.title} - ${alarmData.signaltype} ${alarmData.signallevel} - 时间: ${alarmData.effective}`;
            alarmMarquee.textContent = alarmText;
            
            // 添加跑马灯样式类
            alarmMarquee.classList.add('marquee');
            
            // 添加预警可见类来调整布局
            weatherContainer.classList.add('alarm-visible');
        } else {
            // 隐藏预警信息
            alarmInfo.classList.add('hidden');
            alarmInfo.classList.remove('visible');
            
            // 移除预警可见类
            weatherContainer.classList.remove('alarm-visible');
        }
    }



    getHourlyData(day) {
        const hourKey = `hour${day}`;
        return this.weatherData[hourKey];
    }

    getWeatherIcon(iconUrl) {
        // 调试：打印原始图标URL
        //console.log('原始图标URL:', iconUrl);
        
        // 处理图标URL，确保格式正确
        if (typeof iconUrl === 'string') {
            // 移除可能的引号并修剪空白
            let url = iconUrl.replace(/['`"]/g, '').trim();
            
            // 如果URL已经是完整的http/https链接，直接返回
            if (url.startsWith('http://') || url.startsWith('https://')) {
                return url;
            }
            
            // 如果是数字或简单的图标名称，构造完整的图标URL
            if (/^\d+$/.test(url) || url.endsWith('.png')) {
                // 假设API返回的是图标编号或文件名，构造完整URL
                return `https://rescdn.apihz.cn/resimg/tianqi/${url}`;
            }
            
            return '';
        }
        
        // 如果不是字符串，返回空
        return '';
    }



    showLoading() {
        if (!this.container) return;
        
        const loading = this.container.querySelector('#loading');
        const error = this.container.querySelector('#err');
        const weatherContainer = this.container.querySelector('#weather-container');
        
        loading.classList.remove('hidden');
        error.classList.add('hidden');
        weatherContainer.classList.add('hidden');
    }

    hideLoading() {
        if (!this.container) return;
        
        const loading = this.container.querySelector('#loading');
        const weatherContainer = this.container.querySelector('#weather-container');
        
        loading.classList.add('hidden');
        weatherContainer.classList.remove('hidden');
    }

    showError(message) {
        if (!this.container) return;
        
        const loading = this.container.querySelector('#loading');
        const error = this.container.querySelector('#err');
        const weatherContainer = this.container.querySelector('#weather-container');
        
        loading.classList.add('hidden');
        error.classList.remove('hidden');
        error.textContent = message;
        weatherContainer.classList.add('hidden');
    }

    // 静态方法：初始化天气挂件并传入配置参数
    static init(config = {}) {
        return new WeatherWidget(config);
    }
}

// 获取访问者IP地址的函数
async function getVisitorIp() {
    const apiConfig = getApiConfig();
    
    try {
        const response = await fetch(`https://cn.apihz.cn/api/ip/getapi.php?id=${apiConfig.API_ID}&key=${apiConfig.API_KEY}`);
        if (!response.ok) {
            console.error('HTTP响应错误:', response.status, response.statusText);
            return null;
        }
        
        const data = await response.json();
        if (data.code === 200 && data.ip) {
            return data.ip;
        } else {
            console.error('API返回错误:', data.msg || '未知错误');
            return null;
        }
    } catch (error) {
        console.error('获取IP地址失败:', error);
        return null;
    }
}

// 页面加载完成后初始化天气挂件
document.addEventListener('DOMContentLoaded', () => {
    new WeatherWidget();
});