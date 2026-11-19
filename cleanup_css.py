import os
import re

directory = 'resources/views'

# We'll match two patterns:
# 1. @section('styles') ... @endsection
# 2. <style> ... </style> (if not inside @section)

pattern_section = re.compile(r"@section\('styles'\)\s*<style>.*?</style>\s*@endsection", re.DOTALL)
pattern_style = re.compile(r"<style>.*?</style>", re.DOTALL)

for root, dirs, files in os.walk(directory):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Remove @section('styles') block
            new_content = pattern_section.sub('', content)
            
            # If there's any remaining <style> block directly (e.g. in layouts/app.blade.php without section)
            new_content = pattern_style.sub('', new_content)
            
            # Additional cleanup for multiple empty lines left behind
            new_content = re.sub(r'\n\s*\n\s*\n', '\n\n', new_content)

            if new_content != content:
                print(f"Cleaned {filepath}")
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
