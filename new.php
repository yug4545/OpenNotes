<?php
require_once 'connect.php';
require_once 'security.php';

// Slug helper function for PHP side (not used for live UI, but for inserting in DB)
function slug($text) {
    $text = strtolower($text);
    $text = preg_replace('~[^\pL\d\s-]+~u', '', $text);
    $text = preg_replace('~[\s-]+~', '-', $text);
    $text = trim($text, '-');
    return $text;
}

if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($dbcon, $_POST['title']);
    $description = mysqli_real_escape_string($dbcon, $_POST['description']);
    $slug = slug($title);
    $date = date('Y-m-d H:i');
    $posted_by = mysqli_real_escape_string($dbcon, $_SESSION['username']);

    $sql = "INSERT INTO posts (title, description, slug, posted_by, date) VALUES('$title', '$description', '$slug', '$posted_by', '$date')";
    mysqli_query($dbcon, $sql) or die("Database error: " . mysqli_error($dbcon));

    $permalink = "p/".mysqli_insert_id($dbcon)."/".$slug;
    
    echo '<div class="cyber-success">
            <div class="cyber-success-icon">✓</div>
            <div class="cyber-success-text">
                <h3>Post PUBLISHED</h3>
                <p>Transporting to new post in 2 seconds...</p>
            </div>
          </div>';
    echo "<meta http-equiv='refresh' content='2; url=$permalink'/>";
} else {
?>

<!-- Add Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="background-shapes">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
</div>

<div class="neon-admin-container">
    <div class="page-header">
        <div class="header-content">
           
            <div class="header-text">
                <h1 class="header-title" data-text="Create New Post">Create New Post</h1>
            </div>
        </div>
    </div>

    <div class="admin-glass-panel">
        <div class="panel-content">

        <form method="POST" class="cyber-form" style="padding: 0 20px;">
            <div class="title-section">
                <div class="floating-input-container">
                    <input type="text" id="title" name="title" class="floating-input" required autofocus autocomplete="off">
                    <label for="title" class="floating-label">
                        <!-- <i class="fas fa-heading"></i> -->
                        Post Title
                    </label>
                    <div class="input-line"></div>
                </div>
                <div class="title-meta">
                    <div class="slug-preview">
                        <i class="fas fa-link"></i>
                        <span class="slug-label">Permalink:</span>
                        <span id="slug-output" class="slug-value">(waiting for input)</span>
                    </div>
                    <div class="title-char-count">
                        <span id="char-count">0</span> / 100
                    </div>
                </div>
            </div>
            
            <div class="input-group" style="margin-bottom: 20px;">
                <label class="cyber-label">Post DETAILS:</label>
                <div class="editor-toolbar">
                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="formatText('bold')" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatText('italic')" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatText('underline')" title="Underline">
                            <i class="fas fa-underline"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatText('strikethrough')" title="Strikethrough">
                            <i class="fas fa-strikethrough"></i>
                        </button>
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="alignText('left')" title="Align Left">
                            <i class="fas fa-align-left"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="alignText('center')" title="Align Center">
                            <i class="fas fa-align-center"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="alignText('right')" title="Align Right">
                            <i class="fas fa-align-right"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="alignText('justify')" title="Justify">
                            <i class="fas fa-align-justify"></i>
                        </button>
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="formatList('unordered')" title="Bullet List">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="formatList('ordered')" title="Numbered List">
                            <i class="fas fa-list-ol"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="indent('increase')" title="Increase Indent">
                            <i class="fas fa-indent"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="indent('decrease')" title="Decrease Indent">
                            <i class="fas fa-outdent"></i>
                        </button>
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <select class="toolbar-select font-select" onchange="changeFont(this.value)" title="Font Family">
                            <option value="Inter">Inter</option>
                            <option value="Arial">Arial</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Courier New">Courier New</option>
                            <option value="Georgia">Georgia</option>
                        </select>
                        <select class="toolbar-select" onchange="changeFontSize(this.value)" title="Font Size">
                            <option value="1">Small</option>
                            <option value="3" selected>Normal</option>
                            <option value="5">Large</option>
                            <option value="7">Extra Large</option>
                        </select>
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <input type="color" class="color-picker" onchange="changeColor(this.value)" title="Text Color">
                        <input type="color" class="color-picker" onchange="changeBackgroundColor(this.value)" title="Background Color">
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="insertLink()" title="Insert Link">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="insertImage()" title="Insert Image">
                            <i class="fas fa-image"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="insertTable()" title="Insert Table">
                            <i class="fas fa-table"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="insertCode()" title="Insert Code Block">
                            <i class="fas fa-code"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="insertQuote()" title="Insert Quote">
                            <i class="fas fa-quote-right"></i>
                        </button>
                    </div>

                    <div class="divider"></div>

                    <div class="toolbar-group">
                        <button type="button" class="toolbar-btn" onclick="clearFormatting()" title="Clear Formatting">
                            <i class="fas fa-remove-format"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="undo()" title="Undo">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button type="button" class="toolbar-btn" onclick="redo()" title="Redo">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>
                <div id="editor" class="rich-editor" contenteditable="true"></div>
                <textarea name="description" id="hidden-content" style="display: none;" required></textarea>
                <div class="input-underline"></div>
            </div>

            <div class="form-actions" style="display: flex; justify-content: flex-end;">
                <button type="submit" name="submit" class="cyber-button">
                    <span class="button-icon">✍</span>
                    PUBLISH QUEST
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #6366f1;
    --primary-light: #818cf8;
    --primary-dark: #4f46e5;
    --secondary: #f43f5e;
    --success: #10b981;
    --warning: #f59e0b;
    --info: #3b82f6;
    --dark: #1e293b;
    --darker: #0f172a;
    --light: #f8fafc;
    --lighter: #ffffff;
    --gray: #94a3b8;
    --gray-light: #e2e8f0;
    --glass: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius: 12px;
    --radius-sm: 8px;
    --radius-lg: 16px;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: linear-gradient(135deg, #f6f8ff 0%, #e9f0ff 100%);
    color: var(--dark);
    margin: 0;
    padding: 0;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(99, 102, 241, 0.03) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

/* Decorative elements */
.background-shapes {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.shape {
    position: absolute;
    opacity: 0.5;
    filter: blur(40px);
}

.shape-1 {
    background: var(--primary-light);
    width: 300px;
    height: 300px;
    border-radius: 50%;
    top: -100px;
    right: -100px;
    animation: float 8s ease-in-out infinite;
}

.shape-2 {
    background: var(--info);
    width: 200px;
    height: 200px;
    border-radius: 50%;
    bottom: -50px;
    left: -50px;
    animation: float 10s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(10px, -10px) rotate(5deg); }
    50% { transform: translate(0, 20px) rotate(0deg); }
    75% { transform: translate(-10px, -15px) rotate(-5deg); }
}

.cyber-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.input-group {
    position: relative;
}

.cyber-label {
    display: block;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.85rem;
}

/* Title Section Styles */
.title-section {
    background: white;
    padding: 20px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 30px;
}

.floating-input-container {
    position: relative;
    margin-bottom: 15px;
}

.floating-input {
    width: 100%;
    padding: 16px;
    font-size: 1.25rem;
    border: none;
    border-radius: var(--radius-sm);
    background: var(--lighter);
    color: var(--darker);
    transition: all 0.3s;
    font-weight: 500;
    outline: none;
}

.floating-label {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.1rem;
    color: var(--gray);
    pointer-events: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.floating-label i {
    font-size: 1rem;
    opacity: 0.7;
}

.floating-input:focus ~ .floating-label,
.floating-input:not(:placeholder-shown) ~ .floating-label {
    top: 0;
    font-size: 0.85rem;
    padding: 0 8px;
    background: white;
    font-weight: 600;
    color: var(--primary);
    transform: translateY(-50%);
}

.input-line {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--gray-light);
    transition: all 0.3s;
}

.floating-input:focus ~ .input-line {
    height: 2px;
    background: var(--primary);
    box-shadow: 0 0 8px var(--primary-light);
}

.title-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    padding: 10px 5px;
}

.slug-preview {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray);
    font-size: 0.9rem;
}

.slug-preview i {
    color: var(--primary);
    font-size: 0.85rem;
}

.slug-label {
    font-weight: 500;
}

.slug-value {
    color: var(--primary);
    font-family: 'Courier New', monospace;
    padding: 2px 6px;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 4px;
    font-size: 0.85rem;
}

.title-char-count {
    font-size: 0.85rem;
    color: var(--gray);
    font-weight: 500;
}

.title-char-count.near-limit {
    color: var(--warning);
}

.title-char-count.at-limit {
    color: var(--secondary);
}

/* Regular input styles for other inputs */
.cyber-input, .cyber-textarea {
    width: 100%;
    padding: 12px 15px;
    font-size: 1rem;
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-light);
    outline: none;
    transition: border-color 0.3s;
    font-family: 'Inter', sans-serif;
    color: var(--dark);
    background: white;
    resize: vertical;
}

.cyber-input:focus, .cyber-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 8px var(--primary-light);
}

.form-actions {
    margin-top: 10px;
}

.cyber-button {
    background: var(--primary);
    color: white;
    padding: 12px 24px;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.cyber-button:hover {
    background: var(--primary-dark);
}

.button-icon {
    font-size: 1.2rem;
}

.cyber-success {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 2rem;
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid var(--success);
    border-radius: var(--radius-sm);
    max-width: 600px;
    margin: 2rem auto;
}

.cyber-success-icon {
    font-size: 2.5rem;
    color: var(--success);
    animation: bounce 0.5s;
}

@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

.cyber-success-text h3 {
    color: var(--success);
    margin: 0 0 0.5rem 0;
    letter-spacing: 1px;
}

.cyber-success-text p {
    color: var(--dark);
    margin: 0;
    opacity: 0.8;
}

/* Rich Text Editor Styles */
.editor-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    padding: 10px;
    background: white;
    border: 1px solid var(--gray-light);
    border-bottom: none;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.toolbar-group {
    display: flex;
    gap: 2px;
    align-items: center;
}

.toolbar-btn {
    width: 36px;
    height: 36px;
    padding: 0;
    background: none;
    border: 1px solid transparent;
    border-radius: var(--radius-sm);
    cursor: pointer;
    color: var(--dark);
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.toolbar-btn:hover {
    background: var(--gray-light);
    color: var(--primary);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.toolbar-btn:active {
    transform: translateY(0);
}

.toolbar-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary-dark);
}

.divider {
    width: 1px;
    height: 24px;
    background: var(--gray-light);
    margin: 0 8px;
}

.toolbar-select {
    height: 36px;
    padding: 0 8px;
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-sm);
    outline: none;
    cursor: pointer;
    background: white;
    color: var(--dark);
    font-size: 14px;
    transition: all 0.2s;
}

.toolbar-select:hover {
    border-color: var(--primary);
}

.toolbar-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

.color-picker {
    width: 36px;
    height: 36px;
    padding: 3px;
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-sm);
    cursor: pointer;
    background: white;
    transition: all 0.2s;
}

.color-picker:hover {
    border-color: var(--primary);
    transform: translateY(-1px);
}

.rich-editor {
    min-height: 300px;
    padding: 20px;
    background: white;
    border: 1px solid var(--gray-light);
    border-radius: 0 0 var(--radius-sm) var(--radius-sm);
    outline: none;
    font-family: 'Inter', sans-serif;
    color: var(--dark);
    overflow-y: auto;
    line-height: 1.6;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.rich-editor:focus {
    border-color: var(--primary);
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 0 2px rgba(99, 102, 241, 0.2);
}

/* Editor Content Styles */
.rich-editor h1, .rich-editor h2, .rich-editor h3 {
    margin: 1.5em 0 0.5em;
    line-height: 1.3;
}

.rich-editor p {
    margin: 0 0 1em;
}

.rich-editor blockquote {
    border-left: 4px solid var(--primary);
    margin: 1.5em 0;
    padding: 1em;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
}

.rich-editor pre {
    background: var(--darker);
    color: var(--light);
    padding: 1em;
    border-radius: var(--radius-sm);
    overflow-x: auto;
    margin: 1.5em 0;
}

.rich-editor table {
    border-collapse: collapse;
    width: 100%;
    margin: 1.5em 0;
}

.rich-editor th, .rich-editor td {
    border: 1px solid var(--gray-light);
    padding: 0.5em;
}

.rich-editor th {
    background: rgba(99, 102, 241, 0.1);
}
.page-header{
    
    display:flex;
    align-items: center;
    justify-content:center;
}

/* Responsive */
@media (max-width: 768px) {
.neon-admin-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 40px 20px;
}

.page-header {
    margin-bottom: 50px;
    position: relative;
    padding: 60px 0;
}

.header-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 25px;
    padding: 40px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: var(--radius-lg);
    box-shadow: 
        0 10px 40px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.8) inset;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
    max-width: 600px;
    margin: 0 auto;
}

.header-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, 
        var(--primary) 0%, 
        var(--info) 50%, 
        var(--primary) 100%
    );
    animation: shimmer 2s infinite linear;
    background-size: 200% 100%;
}

.header-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--info) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: 
        0 10px 20px rgba(99, 102, 241, 0.3),
        0 0 0 10px rgba(99, 102, 241, 0.1);
    animation: pulseIcon 2s infinite;
    position: relative;
}

.header-icon::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    filter: blur(20px);
    opacity: 0.3;
    z-index: -1;
}

.header-text {
    text-align: center;
}

.header-title {
    color: var(--darker);
    font-size: 42px;
    margin: 0;
    font-weight: 800;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, var(--darker) 0%, var(--primary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    position: relative;
}

.header-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: var(--primary);
    border-radius: 3px;
}

.header-subtitle {
    color: var(--gray);
    margin: 20px 0 0;
    font-size: 18px;
    font-weight: 500;
    position: relative;
    padding-top: 15px;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.admin-glass-panel {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    box-shadow: 
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06),
        0 0 0 1px rgba(255, 255, 255, 0.7) inset;
    max-width: 800px;
    margin: 0 auto;
    padding: 40px;
    position: relative;
    overflow: hidden;
}

.panel-content {
    position: relative;
    z-index: 1;
}

.admin-glass-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary) 0%, var(--info) 100%);
}

@keyframes pulseIcon {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@media (max-width: 768px) {
    .neon-admin-container {
        padding: 20px 10px;
    }

    .header-content {
        flex-direction: column;
        padding: 20px;
        text-align: center;
    }

    .header-text {
        text-align: center;
    }

    .admin-glass-panel {
        padding: 20px;
    }
}    .editor-toolbar {
        flex-wrap: wrap;
    }
}
</style>

<script>
// Rich Text Editor Functions
function formatText(command) {
    document.execCommand(command, false, null);
    updateToolbarState();
    updateHiddenContent();
}

function alignText(alignment) {
    document.execCommand('justify' + alignment.charAt(0).toUpperCase() + alignment.slice(1), false, null);
    updateToolbarState();
    updateHiddenContent();
}

function changeFontSize(size) {
    document.execCommand('fontSize', false, size);
    updateHiddenContent();
}

function changeFont(fontName) {
    document.execCommand('fontName', false, fontName);
    updateHiddenContent();
}

function changeColor(color) {
    document.execCommand('foreColor', false, color);
    updateHiddenContent();
}

function changeBackgroundColor(color) {
    document.execCommand('hiliteColor', false, color);
    updateHiddenContent();
}

function formatList(type) {
    const command = type === 'ordered' ? 'insertOrderedList' : 'insertUnorderedList';
    document.execCommand(command, false, null);
    updateToolbarState();
    updateHiddenContent();
}

function indent(direction) {
    const command = direction === 'increase' ? 'indent' : 'outdent';
    document.execCommand(command, false, null);
    updateHiddenContent();
}

function insertLink() {
    const url = prompt('Enter URL:', 'http://');
    if (url) {
        document.execCommand('createLink', false, url);
        updateHiddenContent();
    }
}

function insertImage() {
    const url = prompt('Enter image URL:', 'http://');
    if (url) {
        document.execCommand('insertImage', false, url);
        updateHiddenContent();
    }
}

function insertTable() {
    const rows = prompt('Enter number of rows:', '3');
    const cols = prompt('Enter number of columns:', '3');
    if (rows && cols) {
        let table = '<table border="1">';
        for (let i = 0; i < rows; i++) {
            table += '<tr>';
            for (let j = 0; j < cols; j++) {
                table += i === 0 ? '<th>Header</th>' : '<td>Cell</td>';
            }
            table += '</tr>';
        }
        table += '</table>';
        document.execCommand('insertHTML', false, table);
        updateHiddenContent();
    }
}

function insertCode() {
    const selection = window.getSelection();
    const code = '<pre><code>' + (selection.toString() || 'Your code here') + '</code></pre>';
    document.execCommand('insertHTML', false, code);
    updateHiddenContent();
}

function insertQuote() {
    const selection = window.getSelection();
    const quote = '<blockquote>' + (selection.toString() || 'Your quote here') + '</blockquote>';
    document.execCommand('insertHTML', false, quote);
    updateHiddenContent();
}

function clearFormatting() {
    document.execCommand('removeFormat', false, null);
    updateToolbarState();
    updateHiddenContent();
}

function undo() {
    document.execCommand('undo', false, null);
    updateHiddenContent();
}

function redo() {
    document.execCommand('redo', false, null);
    updateHiddenContent();
}

function updateToolbarState() {
    const commands = ['bold', 'italic', 'underline', 'strikethrough', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'];
    commands.forEach(command => {
        const button = document.querySelector(`[onclick="formatText('${command}')"], [onclick="alignText('${command.toLowerCase().replace('justify', '')}')"]`);
        if (button) {
            button.classList.toggle('active', document.queryCommandState(command));
        }
    });
}

function updateHiddenContent() {
    const editor = document.getElementById('editor');
    const hiddenContent = document.getElementById('hidden-content');
    hiddenContent.value = editor.innerHTML;
}

// Initialize the editor
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('editor');
    
    editor.addEventListener('input', () => {
        updateToolbarState();
        updateHiddenContent();
    });
    
    editor.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            document.execCommand('insertHTML', false, '&#009');
        }
    });

    // Initialize toolbar state
    updateToolbarState();
    
    // Add form submit handler to ensure content is saved
    document.querySelector('form').addEventListener('submit', function(e) {
        updateHiddenContent();
    });
});

// CTRL + ENTER shortcut to submit form
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        document.querySelector('form').submit();
    }
});

// Slugify function for live slug display (mimic PHP slug)
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/[^\w\s-]/g, '')   // Remove non-word chars
        .replace(/[\s_-]+/g, '-')   // Replace spaces and underscores with -
        .replace(/^-+|-+$/g, '');   // Trim - from start and end
}

const titleInput = document.getElementById('title');
const slugOutput = document.getElementById('slug-output');
const charCount = document.getElementById('char-count');
const MAX_TITLE_LENGTH = 100;

titleInput.setAttribute('maxlength', MAX_TITLE_LENGTH);

function updateTitleInfo() {
    const length = titleInput.value.length;
    const slug = slugify(titleInput.value);
    
    // Update character count
    charCount.textContent = length;
    charCount.parentElement.classList.toggle('near-limit', length >= MAX_TITLE_LENGTH * 0.8);
    charCount.parentElement.classList.toggle('at-limit', length >= MAX_TITLE_LENGTH * 0.95);
    
    // Update slug
    slugOutput.textContent = slug ? slug : '(waiting for input)';
    
    // Add animation effect when slug changes
    slugOutput.classList.remove('slug-update');
    void slugOutput.offsetWidth; // Trigger reflow
    slugOutput.classList.add('slug-update');
}

titleInput.addEventListener('input', updateTitleInfo);

// Add animation for slug updates
const style = document.createElement('style');
style.textContent = `
    @keyframes slugUpdate {
        0% { transform: translateY(-10px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    .slug-update {
        animation: slugUpdate 0.3s ease-out;
    }
`;
document.head.appendChild(style);
</script>

<?php } ?>
