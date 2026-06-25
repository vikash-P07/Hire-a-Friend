import os
import re

# The directory to search
SEARCH_DIR = r"C:\xampp\htdocs\project\resources\views"

# Mapping of replacements
# NOTE: We are replacing class="text-dark", bg-white, etc.
# Also replacing specific hardcoded colors in style="..." or embedded <style> blocks
REPLACEMENTS = {
    # Bootstrap Utility Classes
    r'\bclass="([^"]*)\btext-dark\b([^"]*)"': r'class="\1text-theme-primary\2"',
    r"\bclass='([^']*)\btext-dark\b([^']*)'": r"class='\1text-theme-primary\2'",
    
    r'\bclass="([^"]*)\btext-black\b([^"]*)"': r'class="\1text-theme-primary\2"',
    r"\bclass='([^']*)\btext-black\b([^']*)'": r"class='\1text-theme-primary\2'",

    r'\bclass="([^"]*)\bbg-white\b([^"]*)"': r'class="\1bg-theme-card\2"',
    r"\bclass='([^']*)\bbg-white\b([^']*)'": r"class='\1bg-theme-card\2'",
    
    r'\bclass="([^"]*)\bbg-light\b([^"]*)"': r'class="\1bg-theme-secondary\2"',
    r"\bclass='([^']*)\bbg-light\b([^']*)'": r"class='\1bg-theme-secondary\2'",

    # Hardcoded CSS colors
    r'#000000\b': 'var(--text-primary)',
    r'#000\b': 'var(--text-primary)',
    r'#111111\b': 'var(--text-primary)',
    r'#222222\b': 'var(--text-primary)',
    r'#333333\b': 'var(--text-secondary)',
    r'#444444\b': 'var(--text-secondary)',
    r'#0b1530\b': 'var(--text-primary)',
}

# Add regex patterns that should be applied strictly
# Example: background: #000000; -> background: var(--bg-inverse);
# We need to be careful with this, but since we are refactoring, let's just do it directly.

# Better: Just replace the specific hardcoded colors in CSS context
CSS_REPLACEMENTS = {
    r'background:\s*#000000;': 'background: var(--bg-inverse);',
    r'background:\s*#000;': 'background: var(--bg-inverse);',
    r'color:\s*#000000;': 'color: var(--text-primary);',
    r'color:\s*#000;': 'color: var(--text-primary);',
    r'color:\s*#0b1530;': 'color: var(--text-primary);',
    r'background:\s*#0b1530;': 'background: var(--bg-primary);',
}

files_modified = 0
colors_replaced = 0

for root, _, files in os.walk(SEARCH_DIR):
    for filename in files:
        if filename.endswith(".blade.php"):
            filepath = os.path.join(root, filename)
            try:
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()

                original_content = content

                for pattern, replacement in CSS_REPLACEMENTS.items():
                    content, count = re.subn(pattern, replacement, content, flags=re.IGNORECASE)
                    colors_replaced += count
                    
                # A custom function to safely replace classes without double-replacing
                def replace_class(match, old_class, new_class):
                    classes = match.group(0)
                    if old_class in classes:
                        return classes.replace(old_class, new_class)
                    return classes
                
                # Manual replacement for classes to ensure we don't break regex logic
                class_mappings = [
                    ('text-dark', 'text-theme-primary'),
                    ('text-black', 'text-theme-primary'),
                    ('bg-white', 'bg-theme-card'),
                    ('bg-light', 'bg-theme-secondary')
                ]
                
                for old_cls, new_cls in class_mappings:
                    # Match class="..."
                    pattern_double = rf'(class="[^"]*?\b){old_cls}(\b[^"]*?")'
                    content, count1 = re.subn(pattern_double, rf'\1{new_cls}\2', content)
                    colors_replaced += count1
                    
                    # Match class='...'
                    pattern_single = rf"(class='[^']*?\b){old_cls}(\b[^']*?')"
                    content, count2 = re.subn(pattern_single, rf'\1{new_cls}\2', content)
                    colors_replaced += count2

                # Replace raw hex codes last, excluding places where they might be used as part of another color or already replaced
                # Only if they are standalone
                for hex_code, replacement in [
                    (r'#000000', 'var(--text-primary)'), 
                    (r'#0b1530', 'var(--text-primary)')
                ]:
                    # We only replace if they are preceded by color: or similar? The user requested them generally replaced.
                    # But if we blindly replace, it might break scripts. 
                    # Let's limit raw hex replacements to style="..." or embedded <style>
                    pass # Handled by CSS_REPLACEMENTS

                if content != original_content:
                    with open(filepath, 'w', encoding='utf-8') as f:
                        f.write(content)
                    files_modified += 1
            except Exception as e:
                print(f"Error processing {filepath}: {e}")

print(f"FILES_MODIFIED={files_modified}")
print(f"COLORS_REPLACED={colors_replaced}")
