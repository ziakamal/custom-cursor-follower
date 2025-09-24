# 🔧 Troubleshooting Guide

## Common Issues & Solutions

### Cursor Not Appearing
**Problem**: The cursor doesn't show up at all
**Solutions**:
- Clear browser cache and cookies
- Check browser console for JavaScript errors
- Ensure the code is in the footer, not header
- Disable other cursor-related plugins

### Cursor Too Slow/Fast
**Problem**: Animation feels sluggish or too quick
**Solution**: Adjust the speed multiplier:
``` javascript
// In the JavaScript section, find this line:
cursorX += (mouseX - cursorX) * 0.3;

// Change 0.3 to:
// 0.2 for slower movement
// 0.4 for faster movement
```

##  Mobile Issues
Problem: Cursor appears on mobile devices
Solution: The code automatically hides on mobile. If you're still seeing it:
```
@media (max-width: 768px) {
    .wp-custom-cursor { display: none !important; }
}
```
