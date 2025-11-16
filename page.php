<?php
// Get page ID from URL (sanitized)
$page = isset($_GET['id']) ? preg_replace("/[^a-zA-Z_]/", "", $_GET['id']) : '';
if ($page == '') {
    $page = 'index';
}

// Helper function to extract content between HTML tags
function get_between_tags($content, $tag) {
    $beg = strpos($content, "<$tag");
    if ($beg !== FALSE) {
        $beg = strpos($content, ">", $beg) + 1;
    }
    $end = strpos($content, "</$tag>");
    if ($end === FALSE) {
        $end = strlen($content);
    }
    return substr($content, $beg, $end - $beg);
}

// Helper function to get body content from HTML file
function get_body($fn) {
    if (!file_exists($fn)) {
        return "<h1>Page Not Found</h1><p>The requested page could not be found.</p>";
    }
    $content = file_get_contents($fn);
    return get_between_tags($content, "body");
}

// Load the content
$filename = "gocoin_$page.html";
$content = get_body($filename);
$title = get_between_tags($content, "h1");
if (empty($title)) {
    $title = "Gocoin";
}

// Define menu structure
$menu_items = [
    'index' => 'About Gocoin',
    'news' => 'Latest News',
    'installation' => 'Installation',
    'manual' => [
        'title' => 'User Manual',
        'items' => [
            'manual_client' => 'Client Node',
            'manual_config' => 'Config File',
            'manual_wallet' => 'Setup Wallet',
            'manual_spending' => 'Spending BTC',
            'manual_multisig' => 'Multi-signature'
        ]
    ],
    'performance' => 'Performance',
    'tweaks' => 'Tweaks',
    'issues' => 'Known Issues',
    'links' => 'Links / Contact'
];

// Function to generate menu HTML
function generate_menu($items, $current_page) {
    $html = '<ul class="nav-menu">';
    
    foreach ($items as $key => $value) {
        if (is_array($value)) {
            // Section with submenu
            $html .= '<li class="nav-section">';
            $html .= '<span class="section-title">' . htmlspecialchars($value['title']) . '</span>';
            $html .= '<ul class="sub-menu">';
            foreach ($value['items'] as $subkey => $subvalue) {
                $active = ($current_page == $subkey) ? ' active' : '';
                $html .= '<li><a href="#" onclick="loadPage(\'' . htmlspecialchars($subkey) . '\'); return false;" class="nav-link' . $active . '" data-page="' . htmlspecialchars($subkey) . '">' . htmlspecialchars($subvalue) . '</a></li>';
            }
            $html .= '</ul></li>';
        } else {
            // Regular menu item
            $active = ($current_page == $key) ? ' active' : '';
            $html .= '<li><a href="#" onclick="loadPage(\'' . htmlspecialchars($key) . '\'); return false;" class="nav-link' . $active . '" data-page="' . htmlspecialchars($key) . '">' . htmlspecialchars($value) . '</a></li>';
        }
    }
    
    $html .= '</ul>';
    return $html;
}

$menu_html = generate_menu($menu_items, $page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gocoin - <?php echo htmlspecialchars($title); ?></title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <style>
/* ===== CSS Variables ===== */
:root {
    --primary-color: #f7931a;
    --primary-dark: #d87a0d;
    --secondary-color: #4a5568;
    --background: #ffffff;
    --sidebar-bg: #1a202c;
    --sidebar-hover: #2d3748;
    --content-bg: #f7fafc;
    --text-primary: #2d3748;
    --text-secondary: #4a5568;
    --text-light: #718096;
    --border-color: #e2e8f0;
    --code-bg: #f7fafc;
    --code-text: #2d6a4f;
    --link-color: #3182ce;
    --link-hover: #2c5282;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --sidebar-width: 280px;
}

/* ===== Reset & Base Styles ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: var(--text-primary);
    background: var(--content-bg);
    overflow-x: hidden;
}

/* ===== Mobile Menu Toggle ===== */
.mobile-menu-toggle {
    display: none;
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 1001;
    background: var(--primary-color);
    border: none;
    border-radius: 8px;
    width: 50px;
    height: 50px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
}

.mobile-menu-toggle:hover {
    background: var(--primary-dark);
    transform: scale(1.05);
}

.mobile-menu-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background: white;
    border-radius: 2px;
    transition: var(--transition);
}

.mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(8px, 8px);
}

.mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

/* ===== Sidebar Navigation ===== */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    color: white;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
    z-index: 1000;
}

.sidebar::-webkit-scrollbar {
    width: 8px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.3);
}

.logo-container {
    padding: 30px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
}

.logo {
    width: 100px;
    height: 100px;
    margin-bottom: 15px;
    transition: var(--transition);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
}

.logo:hover {
    transform: scale(1.05) rotate(5deg);
}

.site-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, #ffb84d 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ===== Navigation Menu ===== */
.nav-menu {
    list-style: none;
    padding: 20px 0;
}

.nav-menu > li {
    margin: 5px 0;
}

.nav-menu a {
    display: block;
    padding: 12px 25px;
    color: #cbd5e0;
    text-decoration: none;
    transition: var(--transition);
    font-size: 15px;
    border-left: 3px solid transparent;
}

.nav-menu a:hover {
    background: var(--sidebar-hover);
    color: white;
    border-left-color: var(--primary-color);
    padding-left: 30px;
}

.nav-menu a.active {
    background: var(--sidebar-hover);
    color: white;
    border-left-color: var(--primary-color);
    font-weight: 600;
}

/* ===== Section Title ===== */
.nav-section {
    margin: 10px 0;
}

.section-title {
    display: block;
    padding: 12px 25px;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sub-menu {
    list-style: none;
    margin-left: 0;
}

.sub-menu a {
    padding: 10px 25px 10px 45px;
    font-size: 14px;
}

/* ===== Main Content ===== */
.main-content {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    background: var(--content-bg);
    transition: var(--transition);
}

.content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 60px;
    background: var(--background);
    min-height: 100vh;
    box-shadow: var(--shadow-sm);
    animation: fadeIn 0.4s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== Typography ===== */
h1 {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 3px solid var(--primary-color);
    position: relative;
}

h1::after {
    content: '';
    position: absolute;
    bottom: -3px;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--primary-dark);
}

h2 {
    font-size: 28px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 30px 0 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--border-color);
}

h3 {
    font-size: 22px;
    font-weight: 600;
    color: var(--text-secondary);
    margin: 25px 0 12px;
}

h4 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-secondary);
    margin: 20px 0 10px;
}

p {
    margin: 15px 0;
    color: var(--text-primary);
    line-height: 1.8;
}

/* ===== Links ===== */
.content-wrapper a {
    color: var(--link-color);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
}

.content-wrapper a:hover {
    color: var(--link-hover);
}

.content-wrapper a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--link-hover);
    transition: width 0.3s ease;
}

.content-wrapper a:hover::after {
    width: 100%;
}

/* ===== Code Blocks ===== */
code {
    background: var(--code-bg);
    color: var(--code-text);
    padding: 3px 8px;
    border-radius: 4px;
    font-family: 'Courier New', Courier, monospace;
    font-size: 14px;
    border: 1px solid var(--border-color);
}

pre {
    background: var(--code-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    overflow-x: auto;
    margin: 20px 0;
    box-shadow: var(--shadow-sm);
}

pre code {
    background: none;
    border: none;
    padding: 0;
}

/* ===== Lists ===== */
ul, ol {
    margin: 15px 0;
    padding-left: 30px;
}

li {
    margin: 8px 0;
    line-height: 1.7;
}

.content-wrapper ul li {
    position: relative;
    padding-left: 10px;
}

.content-wrapper ul li::before {
    content: '▸';
    position: absolute;
    left: -15px;
    color: var(--primary-color);
    font-weight: bold;
}

/* ===== Images ===== */
img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: var(--shadow-md);
    margin: 20px 0;
    transition: var(--transition);
}

img:hover {
    transform: scale(1.02);
    box-shadow: var(--shadow-lg);
}

.logo {
    box-shadow: none;
    margin: 0 0 15px 0;
}

.logo:hover {
    box-shadow: none;
}

/* ===== Tables ===== */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    box-shadow: var(--shadow-sm);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

tbody tr:hover {
    background: #e6f2ff;
}

tr.even {
    background: #fcfff8;
}

tr.odd {
    background: #f8f8ff;
}

/* ===== Special Classes ===== */
.cfg_name {
    font-family: 'Courier New', Courier, monospace;
    font-weight: bold;
    color: var(--code-text);
}

.cfg_type {
    font-weight: bold;
    color: var(--text-secondary);
}

.cfg_info {
    font-style: italic;
    color: var(--text-light);
}

.bigger {
    font-size: 120%;
}

b, strong {
    font-weight: 600;
    color: var(--text-primary);
}

i, em {
    font-style: italic;
    color: var(--text-secondary);
}

/* ===== Responsive Design ===== */
@media (max-width: 1024px) {
    .content-wrapper {
        padding: 30px 40px;
    }
    
    h1 {
        font-size: 32px;
    }
    
    h2 {
        font-size: 24px;
    }
}

@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: flex;
    }
    
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.active {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .content-wrapper {
        padding: 80px 20px 30px;
    }
    
    h1 {
        font-size: 28px;
    }
    
    h2 {
        font-size: 22px;
    }
    
    h3 {
        font-size: 18px;
    }
    
    .logo {
        width: 80px;
        height: 80px;
    }
    
    .site-title {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .content-wrapper {
        padding: 70px 15px 20px;
    }
    
    h1 {
        font-size: 24px;
    }
    
    h2 {
        font-size: 20px;
    }
    
    pre {
        padding: 15px;
        font-size: 12px;
    }
}
    </style>
</head>
<body>
    <!-- Mobile menu toggle -->
    <button class="mobile-menu-toggle" id="menuToggle" aria-label="Toggle menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Sidebar Navigation -->
    <nav class="sidebar" id="sidebar">
        <div class="logo-container">
            <a href="index">
                <img src="logo.png" alt="Gocoin Logo" class="logo">
            </a>
            <h1 class="site-title">Gocoin</h1>
        </div>
        
        <?php echo $menu_html; ?>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content" id="mainContent">
        <div class="content-wrapper">
            <?php echo $content; ?>
        </div>
    </main>

    <script>
        let sidebarScrollPosition = 0;
        
        // Load page using AJAX
        function loadPage(pageName) {
            if (!pageName) pageName = 'index';
            
            console.log('Loading page:', pageName);
            
            const sidebar = document.getElementById('sidebar');
            const contentWrapper = document.querySelector('.content-wrapper');
            
            // Save current sidebar scroll position
            if (sidebar) {
                sidebarScrollPosition = sidebar.scrollTop;
            }
            
            // Update active menu state
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('data-page') === pageName) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
            
            // Show loading state
            contentWrapper.style.opacity = '0.5';
            
            const filename = 'gocoin_' + pageName + '.html';
            
            // Use XMLHttpRequest for better compatibility
            const xhr = new XMLHttpRequest();
            xhr.open('GET', filename, true);
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Extract body content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(xhr.responseText, 'text/html');
                    const body = doc.querySelector('body');
                    
                    if (body) {
                        contentWrapper.innerHTML = body.innerHTML;
                        contentWrapper.style.opacity = '1';
                        
                        // Update external links
                        setTimeout(updateExternalLinks, 100);
                        
                        // Restore sidebar scroll position
                        if (sidebar) {
                            sidebar.scrollTop = sidebarScrollPosition;
                        }
                        
                        // Update URL without reload
                        if (window.history && window.history.pushState) {
                            window.history.pushState({page: pageName}, '', pageName);
                        }
                        
                        // Scroll main content to top (not sidebar!)
                        const mainContent = document.getElementById('mainContent');
                        if (mainContent) {
                            mainContent.scrollTop = 0;
                        }
                    }
                } else {
                    contentWrapper.innerHTML = '<div style="padding: 40px;"><h2>Page Not Found</h2><p>Could not load the requested page.</p></div>';
                    contentWrapper.style.opacity = '1';
                    
                    // Restore sidebar scroll position even on error
                    if (sidebar) {
                        sidebar.scrollTop = sidebarScrollPosition;
                    }
                }
            };
            
            xhr.onerror = function() {
                contentWrapper.innerHTML = '<div style="padding: 40px;"><h2>Error</h2><p>Failed to load the page.</p></div>';
                contentWrapper.style.opacity = '1';
                
                // Restore sidebar scroll position even on error
                if (sidebar) {
                    sidebar.scrollTop = sidebarScrollPosition;
                }
            };
            
            xhr.send();
            
            // Close mobile menu if open
            closeMobileMenu();
            
            return false;
        }
        
        // Update external links to open in new tabs
        function updateExternalLinks() {
            const links = document.querySelectorAll('.content-wrapper a');
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && (href.startsWith('http://') || href.startsWith('https://'))) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                }
            });
        }
        
        // Mobile menu functionality
        function closeMobileMenu() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (menuToggle) menuToggle.classList.remove('active');
            if (sidebar) sidebar.classList.remove('active');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    this.classList.toggle('active');
                    sidebar.classList.toggle('active');
                });
            }
            
            // Close menu when clicking outside on mobile
            if (mainContent) {
                mainContent.addEventListener('click', function(e) {
                    // Don't close if clicking on content links
                    if (e.target.tagName !== 'A' && sidebar.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });
            }
            
            // Track sidebar scroll position
            if (sidebar) {
                sidebar.addEventListener('scroll', function() {
                    sidebarScrollPosition = sidebar.scrollTop;
                });
            }
            
            // Update external links on initial load
            updateExternalLinks();
            
            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(e) {
                if (e.state && e.state.page) {
                    loadPage(e.state.page);
                }
            });
        });
    </script>
</body>
</html>
