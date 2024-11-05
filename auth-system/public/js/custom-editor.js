class CustomEditor {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        this.setupEditor();
        this.setupToolbar();
        this.setupEventListeners();
        this.currentColor = '#000000';

        // Add styles
        const styles = `
        .editor-content {
            font-family: var(--bs-body-font-family);
            line-height: 1.6;
            padding: 1rem;
        }

        .editor-content h1 {
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            margin-top: 2rem !important;
            margin-bottom: 1rem !important;
            color: #1a1a1a !important;
            line-height: 1.2 !important;
        }

        .editor-content h2 {
            font-size: 2rem !important;
            font-weight: 700 !important;
            margin-top: 1.8rem !important;
            margin-bottom: 1rem !important;
            color: #1a1a1a !important;
            line-height: 1.2 !important;
        }

        .editor-content h3 {
            font-size: 1.75rem !important;
            font-weight: 600 !important;
            margin-top: 1.6rem !important;
            margin-bottom: 1rem !important;
            color: #1a1a1a !important;
            line-height: 1.3 !important;
        }

        .editor-content h4 {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
            margin-top: 1.4rem !important;
            margin-bottom: 0.8rem !important;
            color: #2a2a2a !important;
            line-height: 1.3 !important;
        }

        .editor-content h5 {
            font-size: 1.25rem !important;
            font-weight: 600 !important;
            margin-top: 1.2rem !important;
            margin-bottom: 0.8rem !important;
            color: #2a2a2a !important;
            line-height: 1.4 !important;
        }

        .editor-content h6 {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            margin-top: 1rem !important;
            margin-bottom: 0.8rem !important;
            color: #2a2a2a !important;
            line-height: 1.4 !important;
        }

        .editor-content p {
            font-size: 1.1rem !important;
            margin-bottom: 1.2rem !important;
            line-height: 1.6 !important;
        }
    `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = styles;
        document.head.appendChild(styleSheet);
    }

    setupEditor() {
        this.toolbar = document.createElement('div');
        this.toolbar.className = 'editor-toolbar bg-light p-2 border rounded-top';
        
        this.editableArea = document.createElement('div');
        this.editableArea.className = 'editor-content border rounded-bottom p-3';
        this.editableArea.contentEditable = true;
        
        this.hiddenInput = document.createElement('input');
        this.hiddenInput.type = 'hidden';
        this.hiddenInput.name = 'content';

        // Create hidden file input for image uploads
        this.imageInput = document.createElement('input');
        this.imageInput.type = 'file';
        this.imageInput.accept = 'image/*';
        this.imageInput.style.display = 'none';
        
        this.container.appendChild(this.toolbar);
        this.container.appendChild(this.editableArea);
        this.container.appendChild(this.hiddenInput);
        this.container.appendChild(this.imageInput);
    }

    setupToolbar() {
        // Header styles dropdown
        const headerSelect = document.createElement('select');
        headerSelect.className = 'form-select d-inline-block me-2';
        headerSelect.style.width = 'auto';
        
        const headerOptions = [
            { value: 'p', text: 'Paragraph' },
            { value: 'h1', text: 'Header 1' },
            { value: 'h2', text: 'Header 2' },
            { value: 'h3', text: 'Header 3' },
            { value: 'h4', text: 'Subheader 1' },
            { value: 'h5', text: 'Subheader 2' },
            { value: 'h6', text: 'Subheader 3' }
        ];

        headerOptions.forEach(option => {
            const opt = document.createElement('option');
            opt.value = option.value;
            opt.textContent = option.text;
            headerSelect.appendChild(opt);
        });

        headerSelect.addEventListener('change', (e) => {
            this.formatBlock(e.target.value);
        });

        // Color picker
        const colorPicker = document.createElement('input');
        colorPicker.type = 'color';
        colorPicker.className = 'form-control form-control-color me-2';
        colorPicker.title = 'Choose text color';
        colorPicker.value = this.currentColor;
        colorPicker.addEventListener('change', (e) => {
            this.currentColor = e.target.value;
            this.formatText('foreColor', this.currentColor);
        });

        const tools = [
            { icon: 'type-bold', action: () => this.formatText('bold'), title: 'Bold' },
            { icon: 'type-italic', action: () => this.formatText('italic'), title: 'Italic' },
            { icon: 'type-underline', action: () => this.formatText('underline'), title: 'Underline' },
            { type: 'separator' },
            { icon: 'list-ul', action: () => this.formatText('insertUnorderedList'), title: 'Bullet List' },
            { icon: 'list-ol', action: () => this.formatText('insertOrderedList'), title: 'Numbered List' },
            { type: 'separator' },
            { icon: 'image', action: () => this.triggerImageUpload(), title: 'Insert Image' },
            { icon: 'table', action: () => this.insertTable(), title: 'Insert Table' }
        ];

        this.toolbar.appendChild(headerSelect);
        this.toolbar.appendChild(colorPicker);

        tools.forEach(tool => {
            if (tool.type === 'separator') {
                const separator = document.createElement('div');
                separator.className = 'toolbar-separator mx-2';
                this.toolbar.appendChild(separator);
            } else {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'toolbar-btn btn btn-sm btn-light mx-1';
                button.title = tool.title;
                button.innerHTML = `<i class="bi bi-${tool.icon}"></i>`;
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    tool.action();
                });
                this.toolbar.appendChild(button);
            }
        });
    }

    formatBlock(tag) {
        this.editableArea.focus();
        if (tag === 'p') {
            // Special handling for paragraph
            document.execCommand('removeFormat', false, null);
            document.execCommand('formatBlock', false, '<p>');
        } else {
            document.execCommand('formatBlock', false, `<${tag}>`);
        }
        this.updateHiddenInput();
    }

    formatText(command, value = null) {
        this.editableArea.focus();
        document.execCommand(command, false, value);
        this.updateHiddenInput();
    }

    triggerImageUpload() {
        this.savedRange = this.saveSelection();
        this.imageInput.click();
    }

    async handleImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        try {
            const response = await fetch('/upload-image', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                if (this.savedRange) {
                    this.restoreSelection(this.savedRange);
                }
                this.insertHTML(`<img src="${data.url}" alt="Uploaded image" class="img-fluid my-3">`);
            } else {
                alert('Error uploading image');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error uploading image');
        }

        // Clear the input
        this.imageInput.value = '';
    }

    saveSelection() {
        if (window.getSelection) {
            const sel = window.getSelection();
            if (sel.getRangeAt && sel.rangeCount) {
                return sel.getRangeAt(0);
            }
        }
        return null;
    }

    restoreSelection(range) {
        if (range) {
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
    }

    insertHTML(html) {
        this.editableArea.focus();
        document.execCommand('insertHTML', false, html);
        this.updateHiddenInput();
    }

    setupEventListeners() {
        this.editableArea.addEventListener('input', () => {
            this.updateHiddenInput();
        });

        this.imageInput.addEventListener('change', (e) => {
            this.handleImageUpload(e);
        });

        this.editableArea.addEventListener('blur', () => {
            this.savedSelection = this.saveSelection();
        });

        this.editableArea.addEventListener('focus', () => {
            if (this.savedSelection) {
                this.restoreSelection(this.savedSelection);
            }
        });
    }

    updateHiddenInput() {
        this.hiddenInput.value = this
        .editableArea.innerHTML;
    }






    setContent(content) {
        if (content) {
            // Safely set the HTML content
            this.editableArea.innerHTML = content;
            
            // Update the hidden input
            this.updateHiddenInput();
            
            // Update the header select to match current block if cursor is in a header
            this.updateHeaderSelect();
        }
    }

    getContent() {
        return this.editableArea.innerHTML;
    }

    updateHeaderSelect() {
        const headerSelect = this.toolbar.querySelector('select');
        if (!headerSelect) return;

        const selection = window.getSelection();
        if (!selection.rangeCount) return;

        const range = selection.getRangeAt(0);
        let current = range.commonAncestorContainer;
        
        // If we're in a text node, get its parent
        if (current.nodeType === 3) {
            current = current.parentNode;
        }

        // Go up the DOM tree until we find a header or reach the editable area
        while (current && current !== this.editableArea) {
            const tagName = current.tagName ? current.tagName.toLowerCase() : '';
            if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'].includes(tagName)) {
                headerSelect.value = tagName;
                return;
            }
            current = current.parentNode;
        }

        // If no header found, default to paragraph
        headerSelect.value = 'p';
    }

    setupEventListeners() {
        // Add this to your existing event listeners
        this.editableArea.addEventListener('keyup', () => {
            this.updateHeaderSelect();
        });

        this.editableArea.addEventListener('click', () => {
            this.updateHeaderSelect();
        });

        // Your existing event listeners
        this.editableArea.addEventListener('input', () => {
            this.updateHiddenInput();
        });

        this.imageInput.addEventListener('change', (e) => {
            this.handleImageUpload(e);
        });

        // Update header select when selection changes
        document.addEventListener('selectionchange', () => {
            if (document.activeElement === this.editableArea) {
                this.updateHeaderSelect();
            }
        });
    }

    formatBlock(tag) {
        this.editableArea.focus();
        
        // Get the current selection
        const selection = window.getSelection();
        const range = selection.getRangeAt(0);
        
        // Get the current block element
        let block = range.commonAncestorContainer;
        while (block && block.nodeType === 3) {
            block = block.parentNode;
        }
        
        if (tag === 'p') {
            // If converting to paragraph, preserve the text but remove formatting
            const text = block.textContent;
            const p = document.createElement('p');
            p.textContent = text;
            block.parentNode.replaceChild(p, block);
        } else {
            document.execCommand('formatBlock', false, `<${tag}>`);
        }
        
        this.updateHiddenInput();
    }







    insertTable() {
        // Create a modal for table dimensions
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Insert Table</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rows</label>
                            <input type="number" class="form-control" id="tableRows" value="3" min="1" max="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Columns</label>
                            <input type="number" class="form-control" id="tableCols" value="3" min="1" max="10">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="insertTableBtn">Insert</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();

        // Handle table insertion
        document.getElementById('insertTableBtn').addEventListener('click', () => {
            const rows = parseInt(document.getElementById('tableRows').value);
            const cols = parseInt(document.getElementById('tableCols').value);
            
            let tableHTML = '<table class="table table-bordered my-3"><tbody>';
            
            // Create header row
            tableHTML += '<tr>';
            for (let j = 0; j < cols; j++) {
                tableHTML += '<th contenteditable="true">Header ' + (j + 1) + '</th>';
            }
            tableHTML += '</tr>';
            
            // Create data rows
            for (let i = 0; i < rows; i++) {
                tableHTML += '<tr>';
                for (let j = 0; j < cols; j++) {
                    tableHTML += '<td contenteditable="true">Cell</td>';
                }
                tableHTML += '</tr>';
            }
            tableHTML += '</tbody></table>';

            this.insertHTML(tableHTML);
            modalInstance.hide();
            modal.remove();
        });

        // Clean up modal when hidden
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
    }

    insertHTML(html) {
        this.restoreSelection(this.savedSelection);
        document.execCommand('insertHTML', false, html);
        this.updateHiddenInput();
    }


    
}