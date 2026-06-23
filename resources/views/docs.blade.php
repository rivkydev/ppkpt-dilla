<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi PPKPT ITH</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Pure CSS for Markdown (No JS CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.5.0/github-markdown.min.css">
    
    <style>
        :root {
            --bg-body: #f4f4f9;
            --bg-card: #ffffff;
            --text-main: #333333;
            --accent: #4f46e5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background-color: var(--bg-card);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--accent);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand-icon {
            background: var(--accent);
            color: white;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 1rem;
        }
        .navbar-link {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .navbar-link:hover {
            color: var(--accent);
        }

        /* Main Container */
        .container {
            max-width: 900px;
            margin: 100px auto 50px;
            background: var(--bg-card);
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            box-sizing: border-box;
        }

        /* Markdown Overrides */
        .markdown-body {
            font-family: 'Inter', sans-serif !important;
        }
        .markdown-body h1, .markdown-body h2 {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
            color: #111;
        }
        .markdown-body h1 {
            font-size: 2.2rem;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: none;
        }
        .markdown-body a {
            color: var(--accent);
        }
        
        /* Mermaid Diagram */
        .mermaid {
            background-color: #fafafa;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            overflow-x: auto;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: #888;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .container {
                margin: 80px 15px 30px;
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <nav class="navbar">
        <a href="#" class="navbar-brand">
            <div class="navbar-brand-icon">P</div>
            Docs PPKPT
        </a>
        <a href="/" class="navbar-link">&larr; Kembali ke Sistem</a>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <article class="markdown-body">
            {!! $html !!}
        </article>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} Pusat Bantuan PPKPT ITH. All rights reserved.
    </div>

    <!-- Mermaid.js -->
    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';
        mermaid.initialize({ startOnLoad: true, theme: 'default', fontFamily: '"Inter", sans-serif' });
        
        document.addEventListener("DOMContentLoaded", function() {
            const codeBlocks = document.querySelectorAll("pre code.language-mermaid");
            codeBlocks.forEach((block) => {
                const graphDefinition = block.textContent;
                const parentPre = block.parentElement;
                const mermaidDiv = document.createElement('div');
                mermaidDiv.className = 'mermaid';
                mermaidDiv.textContent = graphDefinition;
                parentPre.parentNode.replaceChild(mermaidDiv, parentPre);
            });
            mermaid.init(undefined, document.querySelectorAll('.mermaid'));
        });
    </script>
    <!-- MathJax for Math Formulas (Using unpkg, highly resilient CDN) -->
    <script>
        MathJax = {
            tex: {
                inlineMath: [['$', '$'], ['\\(', '\\)']],
                displayMath: [['$$', '$$'], ['\\[', '\\]']],
                processEscapes: true
            },
            svg: {
                fontCache: 'global'
            }
        };
    </script>
    <script type="text/javascript" id="MathJax-script" async
      src="https://unpkg.com/mathjax@3/es5/tex-svg.js">
    </script>
</body>
</html>
