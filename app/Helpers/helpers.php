<?php

if (!function_exists('getBankLogoUrl')) {
    function getBankLogoUrl($bankName) {
        $bankName = strtolower(trim($bankName));
        if (str_contains($bankName, 'bca')) return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyyDL48YXf0J3DjfcEKeyvCxbT9uJVND1kEQ&s';
        if (str_contains($bankName, 'mandiri')) return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRX5iHirHPd4xIHtMMJU9Bj_MEpJYZygKavQ&s';
        if (str_contains($bankName, 'bni')) return 'https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png';
        if (str_contains($bankName, 'bri')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/1200px-BRI_2020.svg.png';
        if (str_contains($bankName, 'bsi')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/1200px-Bank_Syariah_Indonesia.svg.png';
        if (str_contains($bankName, 'dana')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/1200px-Logo_dana_blue.svg.png';
        if (str_contains($bankName, 'ovo')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/1200px-Logo_ovo_purple.svg.png';
        if (str_contains($bankName, 'gopay')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/1200px-Gopay_logo.svg.png';
        if (str_contains($bankName, 'shopeepay')) return 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f7/ShopeePay_Logo.svg/1200px-ShopeePay_Logo.svg.png';
        
        return 'https://cdn-icons-png.flaticon.com/512/2830/2830284.png'; 
    }
}