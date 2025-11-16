# Gocoin Website - Hybrid Version (Works Everywhere!)

## 🎉 The Perfect Solution - One Version, Works Everywhere!

This is the **best of both worlds** - a single codebase that:
- ✅ Works locally (just double-click `index.html`)
- ✅ Works on web server with clean URLs
- ✅ Looks identical in both environments
- ✅ **Edit once, deploy anywhere!**

---

## 🚀 How to Use

### **Locally (Development & Editing):**
1. Double-click `index.html`
2. Browse and edit your content
3. See changes immediately

### **On Web Server (Production):**
1. Upload all files (including `.htaccess`)
2. Visit your domain
3. Get clean URLs and full PHP functionality

**Same files, zero maintenance overhead!** ✨

---

## 🎯 How It Works

The site **automatically detects** the environment:

### **When Opened Locally** (`file://` protocol):
- Uses JavaScript to load pages dynamically
- Navigation uses hash URLs (`#index`, `#performance`)
- Links work via `href="#page"`
- Perfect for editing and testing

### **When On Web Server** (`http://` or `https://`):
- PHP handles page routing via `page.php`
- `.htaccess` provides clean URLs (`/index`, `/performance`)
- JavaScript enhances the experience
- SEO-friendly, shareable URLs

**One index.html file that adapts to its environment!**

---

## 📁 File Structure

```
website_hybrid/
├── index.html              # Smart hybrid page (works everywhere)
├── page.php                # Server-side handler (used on server)
├── .htaccess               # URL rewriting (used on server)
├── style.css               # Modern styling
├── gocoin_index.html       # Your content pages
├── gocoin_*.html           # More content
├── logo.png                # Assets
├── *.png                   # Images
└── favicon.ico             # Icon
```

---

## 🔧 Environment Detection Logic

The JavaScript automatically detects:

```javascript
// Local detection
const isLocal = window.location.protocol === 'file:';

if (isLocal) {
    // Use hash navigation: #page
    // Load content via fetch()
    // Update links with hash
} else {
    // Use clean URLs: /page
    // Update browser history
    // SEO-friendly routing
}
```

---

## ✨ Features in Both Environments

Both local and server versions have:
- ✅ Modern responsive design
- ✅ Mobile hamburger menu
- ✅ Smooth page transitions
- ✅ Active menu highlighting
- ✅ External links open in new tabs
- ✅ All animations and effects
- ✅ Identical appearance

---

## 🌐 URL Behavior

### **Local Version:**
```
file:///path/to/index.html              → About Gocoin
file:///path/to/index.html#performance  → Performance page
file:///path/to/index.html#installation → Installation
```

### **Server Version:**
```
https://yoursite.com/              → About Gocoin
https://yoursite.com/performance   → Performance page
https://yoursite.com/installation  → Installation
```

**Same content, different URL styles based on environment!**

---

## 🎨 Your Workflow

### 1. **Edit Locally:**
```bash
# Open in browser
open index.html

# Edit content
nano gocoin_performance.html

# Refresh browser to see changes
```

### 2. **Upload to Server:**
```bash
# Upload all files via FTP/SFTP
scp -r * user@server:/var/www/html/

# Or use your hosting control panel
# Just upload everything!
```

### 3. **That's It!**
No build process, no compilation, no separate versions!

---

## 📝 Editing Content

Just edit the `gocoin_*.html` files:

```html
<!-- gocoin_performance.html -->
<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<h1>Performance</h1>
<p>Your content here...</p>
</body>
</html>
```

**Changes work immediately in both local and server environments!**

---

## 🔗 Linking Between Pages

The system handles links automatically:

```html
<!-- Your content can use simple links -->
<a href="performance">See performance</a>

<!-- Or internal page links -->
<a href="#tweaks">Check tweaks</a>

<!-- External links work normally -->
<a href="https://github.com/piotrnar" target="_blank">GitHub</a>
```

---

## 🎯 Advantages Over Separate Versions

| Feature | Hybrid Version | Separate Versions |
|---------|---------------|-------------------|
| **Single codebase** | ✅ Yes | ❌ No |
| **Edit once** | ✅ Yes | ❌ Edit twice |
| **Works locally** | ✅ Yes | ⚠️ One does |
| **Clean URLs on server** | ✅ Yes | ⚠️ One does |
| **Maintenance** | ✅ Zero duplication | ❌ Sync required |
| **Deployment** | ✅ Upload once | ❌ Choose version |

---

## 🚀 Quick Start Guide

### **For Local Development:**

1. **Extract files** to any folder
2. **Double-click** `index.html`
3. **Edit** any `gocoin_*.html` file
4. **Refresh** browser to see changes

### **For Production Deployment:**

1. **Upload** all files to your server
2. **Include** `.htaccess` (it's hidden!)
3. **Verify** file permissions (644 for files)
4. **Visit** your domain
5. **Enjoy** clean URLs and modern design!

---

## 📊 What Happens Behind the Scenes

### **Local Environment:**
```
1. User clicks "Performance"
2. JavaScript detects file:// protocol
3. Loads gocoin_performance.html via fetch()
4. Extracts <body> content
5. Injects into page
6. Updates hash to #performance
```

### **Server Environment:**
```
1. User visits /performance
2. .htaccess rewrites to page.php?id=performance
3. PHP loads gocoin_performance.html
4. Wraps in modern template
5. Returns complete HTML
6. JavaScript enhances experience
```

---

## 🔒 Security Features

- ✅ Input sanitization in PHP (prevents injection)
- ✅ Filename validation (alphanumeric + underscore only)
- ✅ No directory traversal possible
- ✅ Safe fallback for missing pages

---

## 🎨 Customization

All styling in one place:

```css
/* style.css */
:root {
    --primary-color: #f7931a;    /* Bitcoin orange */
    --sidebar-bg: #1a202c;       /* Dark sidebar */
    --link-color: #3182ce;       /* Link blue */
    /* Change any color! */
}
```

Edit once, applies everywhere (local + server).

---

## 🐛 Troubleshooting

### **Local: "Page Not Found"**
- Check that `gocoin_*.html` files are in same folder
- Try opening from file explorer (not web server)
- Check browser console for errors

### **Server: Clean URLs not working**
- Verify `.htaccess` was uploaded (it's hidden!)
- Check mod_rewrite is enabled
- Ensure AllowOverride is set
- Try: `page.php?id=index` directly

### **Server: 500 Error**
- Check PHP is installed and working
- Verify file permissions (644)
- Check Apache error log
- Ensure PHP version ≥ 7.0

---

## 📦 File Checklist

Before uploading, ensure you have:

```
✓ index.html           - Main hybrid page
✓ style.css            - Modern styling  
✓ page.php             - Server handler
✓ .htaccess            - URL rewriting
✓ gocoin_index.html    - About page
✓ gocoin_news.html     - News
✓ gocoin_*.html        - All other pages (9 more)
✓ logo.png             - Logo
✓ favicon.ico          - Icon
✓ *.png                - All charts/images
```

---

## 🎉 Benefits Summary

**One Version That:**
- ✅ Works locally for editing
- ✅ Works on server with clean URLs
- ✅ Looks identical everywhere
- ✅ Zero maintenance overhead
- ✅ Modern responsive design
- ✅ Easy to update
- ✅ Easy to deploy

**This is exactly what you wanted!** 🎯

---

## 💡 Pro Tips

1. **Bookmark locally**: Bookmark `file:///path/to/index.html` for quick access
2. **Version control**: Git-friendly single version
3. **Testing**: Test locally before uploading
4. **Backup**: Keep a backup of your working version
5. **Browser cache**: Clear cache after updates

---

## 🚀 You're Ready!

1. ✅ Extract files
2. ✅ Edit content locally
3. ✅ Test in browser
4. ✅ Upload to server
5. ✅ Profit!

**No more maintaining two separate versions!** 🎉
