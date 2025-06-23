# Solvex - Paint Manufacturing Website

A complete HTML, CSS, JavaScript, and PHP website for Solvex by Enova Industries - a premium paint manufacturing company.

## 🎨 Project Overview

This is a fully converted version of the original React-based Solvex website, now built with traditional web technologies while maintaining the same design, layout, and functionality.

## ✨ Features

- **Responsive Design**: Mobile-first approach with modern CSS Grid and Flexbox
- **Interactive Components**: Testimonials carousel, gallery filtering, FAQ accordion
- **Form Handling**: Contact and dealer application forms with PHP backend
- **Modern UI**: Clean, professional design with smooth animations
- **Cross-browser Compatible**: Works on all modern browsers
- **SEO Optimized**: Semantic HTML structure and meta tags

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+
- **Styling**: Custom CSS with CSS Grid and Flexbox
- **Icons**: Font Awesome 6.0
- **Fonts**: Google Fonts (Poppins)
- **Images**: Unsplash (placeholder images)

## 📁 Project Structure

```
solvex-website/
├── index.html                 # Homepage
├── about.html                 # About page
├── products.html              # Products page
├── gallery.html               # Gallery page
├── dealers.html               # Dealers page
├── faq.html                   # FAQ page
├── contact.html               # Contact page
├── process-contact.php        # Contact form handler
├── process-dealer.php         # Dealer application handler
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   └── js/
│       ├── main.js            # Main JavaScript
│       ├── gallery.js         # Gallery functionality
│       └── faq.js             # FAQ functionality
└── README.md                  # This file
```

## 🚀 Getting Started

### Prerequisites

- Web server with PHP support (Apache, Nginx, or built-in PHP server)
- PHP 7.4 or higher
- Modern web browser

### Installation

1. **Clone or download** the project files to your web server directory

2. **Set up a local server** (choose one option):

   **Option A: PHP Built-in Server**
   ```bash
   php -S localhost:8000
   ```

   **Option B: XAMPP/WAMP/MAMP**
   - Place files in `htdocs` folder
   - Start Apache server
   - Access via `http://localhost/solvex-website`

   **Option C: Live Server (VS Code)**
   - Install Live Server extension
   - Right-click `index.html` and select "Open with Live Server"

3. **Configure Email Settings** (Optional):
   - Edit `process-contact.php` and `process-dealer.php`
   - Update email addresses in the `$to` variables
   - Ensure your server has mail functionality enabled

4. **Access the website**:
   - Open your browser
   - Navigate to `http://localhost:8000` (or your server URL)

## 📧 Form Configuration

### Contact Form
- **File**: `process-contact.php`
- **Email**: Update `$to = 'info@solvex.com'` with your email
- **Logging**: Form submissions are logged to `contact_log.txt`

### Dealer Application Form
- **File**: `process-dealer.php`
- **Email**: Update `$to = 'dealers@solvex.com'` with your email
- **Logging**: Applications are logged to `dealer_applications.txt`

## 🎯 Key Features Explained

### 1. Responsive Navigation
- Sticky navigation with mobile hamburger menu
- Active page highlighting
- Smooth transitions

### 2. Interactive Gallery
- Filterable project showcase
- Category-based filtering (Interior, Exterior, Commercial)
- Smooth animations and hover effects

### 3. Testimonials Carousel
- Auto-rotating testimonials
- Dot navigation
- Smooth transitions

### 4. FAQ Accordion
- Expandable questions and answers
- Smooth animations
- Only one answer visible at a time

### 5. Form Validation
- Client-side validation with JavaScript
- Server-side validation with PHP
- Toast notifications for user feedback

## 🎨 Design System

### Colors
- **Primary**: #dc2626 (Red)
- **Secondary**: #0f172a (Dark Blue)
- **Background**: #f8fafc (Light Gray)
- **Text**: #0f172a (Dark)

### Typography
- **Font Family**: Poppins (Google Fonts)
- **Weights**: 300, 400, 500, 600, 700

### Components
- Cards with subtle shadows
- Rounded corners (0.5rem)
- Consistent spacing
- Hover effects and transitions

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px
- **Desktop**: > 1024px

## 🔧 Customization

### Changing Colors
Edit the CSS variables in `assets/css/style.css`:
```css
:root {
    --primary: 220 38 127;  /* Change this for primary color */
    --accent: 220 38 127;   /* Change this for accent color */
}
```

### Adding New Pages
1. Create new HTML file
2. Copy navigation structure from existing pages
3. Add link to navigation menu
4. Update active state in JavaScript

### Modifying Content
- All content is in HTML files
- Images can be replaced by updating `src` attributes
- Text content can be edited directly in HTML

## 🐛 Troubleshooting

### Common Issues

1. **Forms not working**:
   - Ensure PHP is enabled on your server
   - Check file permissions
   - Verify email configuration

2. **Images not loading**:
   - Check image URLs
   - Ensure internet connection (for Unsplash images)
   - Replace with local images if needed

3. **Styling issues**:
   - Clear browser cache
   - Check CSS file path
   - Verify CSS syntax

### Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## 📄 License

This project is for educational and commercial use. All images are from Unsplash and are free to use.

## 🤝 Contributing

1. Fork the project
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For technical support or questions:
- Check the FAQ page
- Review the code comments
- Contact the development team

---

**Built with ❤️ for Solvex by Enova Industries** 