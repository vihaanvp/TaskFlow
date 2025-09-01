document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('lists-nav')) return; // only run on dashboard

    let currentListId = null;
    let currentListType = 'todo';

    // Show Add List Modal
    document.getElementById('add-list-btn').onclick = function() {
        document.getElementById('add-list-modal').style.display = 'flex';
        document.getElementById('list-title').focus();
    };

    // Hide Add List Modal
    document.getElementById('cancel-add-list').onclick = function() {
        document.getElementById('add-list-modal').style.display = 'none';
        document.getElementById('new-list-form').reset();
    };

    // Add New List
    document.getElementById('new-list-form').onsubmit = function(e) {
        e.preventDefault();
        const title = document.getElementById('list-title').value.trim();
        const type = document.getElementById('list-type').value;
        const description = document.getElementById('list-description').value.trim();
        
        if (!title) return;
        
        fetch('api/lists.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'add', title, type, description}),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('add-list-modal').style.display = 'none';
                    document.getElementById('new-list-form').reset();
                    loadLists(data.id);
                    guiToast('List created!');
                }
            });
    };

    // Load all lists and select the first one
    function loadLists(selectId = null) {
        fetch('api/lists.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'all'}),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const nav = document.getElementById('lists-nav');
                    nav.innerHTML = '';
                    if (data.lists.length === 0) {
                        nav.innerHTML = '<div style="color: #888; margin: 2em 0;">No lists yet.</div>';
                        document.getElementById('main-content').innerHTML = '<p class="no-list-msg">Create a list to get started!</p>';
                        currentListId = null;
                        return;
                    }
                    data.lists.forEach((list, idx) => {
                        const wrap = document.createElement('div');
                        wrap.className = 'list-link-wrap' + ((selectId ? list.id === selectId : idx === 0) ? ' active' : '');
                        
                        // List link with type icon
                        const a = document.createElement('a');
                        a.className = 'list-link' + ((selectId ? list.id === selectId : idx === 0) ? ' active' : '');
                        const typeIcon = list.type === 'note' ? '📝' : '📋';
                        const sharedIcon = list.is_shared ? ' 🤝' : '';
                        a.innerHTML = `${typeIcon} ${escapeHtml(list.title)}${sharedIcon}`;
                        a.href = '#';
                        a.dataset.id = list.id;
                        a.dataset.type = list.type || 'todo';
                        a.onclick = function(e) {
                            e.preventDefault();
                            document.querySelectorAll('.list-link').forEach(l => l.classList.remove('active'));
                            document.querySelectorAll('.list-link-wrap').forEach(l => l.classList.remove('active'));
                            this.classList.add('active');
                            wrap.classList.add('active');
                            loadItems(list.id, list.title, list.type || 'todo', list.is_shared);
                            currentListId = list.id;
                            currentListType = list.type || 'todo';
                        };
                        wrap.appendChild(a);
                        
                        // Action buttons container
                        const actions = document.createElement('div');
                        actions.className = 'list-actions';
                        
                        // Share button (only for owned lists)
                        if (!list.is_shared) {
                            const shareBtn = document.createElement('button');
                            shareBtn.className = 'share-list-btn';
                            shareBtn.textContent = '🔗';
                            shareBtn.title = 'Share List';
                            shareBtn.onclick = function(e) {
                                e.stopPropagation();
                                showShareModal(list.id);
                            };
                            actions.appendChild(shareBtn);
                        }
                        
                        // Delete button (only for owned lists)
                        if (!list.is_shared) {
                            const deleteBtn = document.createElement('button');
                            deleteBtn.className = 'delete-list-btn';
                            deleteBtn.textContent = '🗑️';
                            deleteBtn.title = 'Delete List';
                            deleteBtn.onclick = function(e) {
                                e.stopPropagation();
                                guiConfirm('Delete this list and all its content?').then(confirmed => {
                                    if (confirmed) {
                                        fetch('api/lists.php', {
                                            method: 'POST',
                                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                            body: new URLSearchParams({action: 'delete', id: list.id}),
                                        })
                                            .then(r => r.json())
                                            .then(data => {
                                                if (data.success) {
                                                    loadLists();
                                                    guiToast('List deleted!');
                                                }
                                            });
                                    }
                                });
                            };
                            actions.appendChild(deleteBtn);
                        }
                        
                        wrap.appendChild(actions);
                        nav.appendChild(wrap);
                    });
                    // Select first or newly created
                    let sel = selectId ? selectId : data.lists[0].id;
                    let selList = data.lists.find(l => l.id == sel);
                    currentListId = sel;
                    currentListType = selList.type || 'todo';
                    loadItems(sel, selList.title, selList.type || 'todo', selList.is_shared);
                }
            });
    }

    // Share list modal functions
    function showShareModal(listId) {
        document.getElementById('share-list-id').value = listId;
        document.getElementById('share-list-modal').style.display = 'flex';
        document.getElementById('share-username').focus();
    }

    document.getElementById('cancel-share-list').onclick = function() {
        document.getElementById('share-list-modal').style.display = 'none';
        document.getElementById('share-list-form').reset();
    };

    document.getElementById('share-list-form').onsubmit = function(e) {
        e.preventDefault();
        const listId = document.getElementById('share-list-id').value;
        const username = document.getElementById('share-username').value.trim();
        const permission = document.getElementById('share-permission').value;
        
        if (!username) return;
        
        fetch('api/lists.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'share', list_id: listId, username, permission}),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('share-list-modal').style.display = 'none';
                    document.getElementById('share-list-form').reset();
                    guiToast(data.message || 'List shared successfully!');
                } else {
                    guiAlert(data.error || 'Failed to share list');
                }
            });
    };

    // Help modal
    document.getElementById('help-link').onclick = function(e) {
        e.preventDefault();
        document.getElementById('help-modal').style.display = 'flex';
    };

    document.getElementById('close-help').onclick = function() {
        document.getElementById('help-modal').style.display = 'none';
    };

    // Load items for a list
    function loadItems(listId, listTitle, listType = 'todo', isShared = false) {
        fetch('api/items.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'all', list_id: listId}),
        })
            .then(r => r.json())
            .then(data => {
                const typeIcon = listType === 'note' ? '📝' : '📋';
                const sharedText = isShared ? ' (Shared)' : '';
                let html = `<h2 class="list-title">${typeIcon} ${listTitle}${sharedText}</h2>`;
                
                if (listType === 'todo') {
                    // Render as todo list
                    html += `<ul class="task-list">`;
                    if (data.items.length === 0) {
                        html += `<li style="color:#888;">No tasks yet.</li>`;
                    }
                    data.items.forEach(item => {
                        html += `<li class="task-item${item.is_done ? ' done' : ''}" data-id="${item.id}">
                        <input type="checkbox" ${item.is_done ? 'checked' : ''} class="toggle-task">
                        <span>${escapeHtml(item.content)}</span>
                        <button class="delete-task" title="Delete">🗑️</button>
                    </li>`;
                    });
                    html += `</ul>
                <form id="add-item-form" autocomplete="off">
                    <input type="text" id="new-item-content" placeholder="Add a new task..." maxlength="255" required>
                    <button type="submit">Add Task</button>
                </form>`;
                } else {
                    // Render as note page
                    html += `<div class="note-content">`;
                    if (data.items.length === 0) {
                        html += `<p style="color:#888;">Click below to start writing...</p>`;
                    }
                    data.items.forEach(item => {
                        html += `<div class="note-item" data-id="${item.id}">
                        <div class="note-text">${escapeHtml(item.content)}</div>
                        <button class="delete-note" title="Delete">🗑️</button>
                    </div>`;
                    });
                    html += `</div>
                <form id="add-item-form" autocomplete="off">
                    <textarea id="new-item-content" placeholder="Write your note here..." rows="4" required></textarea>
                    <button type="submit">Add Note</button>
                </form>`;
                }
                
                document.getElementById('main-content').innerHTML = html;

                // Add Item
                document.getElementById('add-item-form').onsubmit = function(e) {
                    e.preventDefault();
                    const content = document.getElementById('new-item-content').value.trim();
                    if (!content) return;
                    
                    const itemType = listType === 'note' ? 'content' : 'task';
                    
                    fetch('api/items.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({action: 'add', list_id: listId, content, item_type: itemType}),
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                loadItems(listId, listTitle, listType, isShared);
                                guiToast(listType === 'note' ? 'Note added!' : 'Task added!');
                            }
                        });
                };

                // Toggle and Delete for tasks
                if (listType === 'todo') {
                    document.querySelectorAll('.task-item').forEach(li => {
                        // Toggle done
                        li.querySelector('.toggle-task').onchange = function() {
                            fetch('api/items.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                body: new URLSearchParams({
                                    action: 'toggle',
                                    id: li.dataset.id,
                                    is_done: this.checked ? 1 : 0
                                }),
                            }).then(() => {
                                loadItems(listId, listTitle, listType, isShared);
                            });
                        };
                        // Delete
                        li.querySelector('.delete-task').onclick = function() {
                            guiConfirm('Delete this task?').then(confirmed => {
                                if (confirmed) {
                                    fetch('api/items.php', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                        body: new URLSearchParams({
                                            action: 'delete',
                                            id: li.dataset.id
                                        }),
                                    }).then(() => {
                                        loadItems(listId, listTitle, listType, isShared);
                                        guiToast('Task deleted!');
                                    });
                                }
                            });
                        };
                    });
                } else {
                    // Delete for notes
                    document.querySelectorAll('.note-item').forEach(noteDiv => {
                        noteDiv.querySelector('.delete-note').onclick = function() {
                            guiConfirm('Delete this note?').then(confirmed => {
                                if (confirmed) {
                                    fetch('api/items.php', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                        body: new URLSearchParams({
                                            action: 'delete',
                                            id: noteDiv.dataset.id
                                        }),
                                    }).then(() => {
                                        loadItems(listId, listTitle, listType, isShared);
                                        guiToast('Note deleted!');
                                    });
                                }
                            });
                        };
                    });
                }
            });
    }

    // Helper to escape HTML (for task content)
    function escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Search functionality
    let searchTimeout;
    document.getElementById('search-input').oninput = function() {
        const query = this.value.trim();
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            document.getElementById('search-results').style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch('api/search.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({action: 'search', query}),
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showSearchResults(data.results);
                    }
                });
        }, 300);
    };

    function showSearchResults(results) {
        const container = document.getElementById('search-results');
        
        if (results.length === 0) {
            container.innerHTML = '<div class="search-no-results">No results found</div>';
            container.style.display = 'block';
            return;
        }
        
        let html = '';
        results.forEach(result => {
            const typeIcon = result.list_type === 'note' ? '📝' : '📋';
            const sourceIcon = result.source === 'shared' ? ' 🤝' : '';
            
            html += `<div class="search-result" onclick="loadItems(${result.list_id}, '${escapeHtml(result.list_title)}', '${result.list_type}', ${result.source === 'shared'})">
                <div class="search-list-title">${typeIcon} ${escapeHtml(result.list_title)}${sourceIcon}</div>`;
            
            if (result.items.length > 0) {
                html += '<div class="search-items">';
                result.items.slice(0, 3).forEach(item => {
                    html += `<div class="search-item">• ${escapeHtml(item.content.substring(0, 60))}${item.content.length > 60 ? '...' : ''}</div>`;
                });
                if (result.items.length > 3) {
                    html += `<div class="search-item">... and ${result.items.length - 3} more</div>`;
                }
                html += '</div>';
            }
            
            html += '</div>';
        });
        
        container.innerHTML = html;
        container.style.display = 'block';
    }

    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-container')) {
            document.getElementById('search-results').style.display = 'none';
        }
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('search-input').focus();
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            document.getElementById('add-list-modal').style.display = 'none';
            document.getElementById('share-list-modal').style.display = 'none';
            document.getElementById('help-modal').style.display = 'none';
            document.getElementById('search-results').style.display = 'none';
        }
        
        // Ctrl/Cmd + N to create new list
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            document.getElementById('add-list-btn').click();
        }
    });

    // Close modals when clicking backdrop
    document.getElementById('add-list-modal').onclick = function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    };
    
    document.getElementById('share-list-modal').onclick = function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    };

    document.getElementById('help-modal').onclick = function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    };

    // Initial load
    loadLists();
});

// Custom confirm modal
function guiConfirm(msg) {
    return new Promise((resolve) => {
        const backdrop = document.getElementById('modal-backdrop');
        const modalMsg = document.getElementById('gui-modal-message');
        const modalBtns = document.getElementById('gui-modal-buttons');
        modalMsg.textContent = msg;
        modalBtns.innerHTML = '';
        // Confirm
        const ok = document.createElement('button');
        ok.className = 'gui-modal-btn ok';
        ok.textContent = 'OK';
        ok.onclick = () => { backdrop.style.display = 'none'; resolve(true); };
        // Cancel
        const cancel = document.createElement('button');
        cancel.className = 'gui-modal-btn cancel';
        cancel.textContent = 'Cancel';
        cancel.onclick = () => { backdrop.style.display = 'none'; resolve(false); };
        modalBtns.appendChild(ok);
        modalBtns.appendChild(cancel);
        backdrop.style.display = 'flex';
        setTimeout(() => ok.focus(), 50);
    });
}
// Custom alert modal (just OK)
function guiAlert(msg) {
    return new Promise((resolve) => {
        const backdrop = document.getElementById('modal-backdrop');
        const modalMsg = document.getElementById('gui-modal-message');
        const modalBtns = document.getElementById('gui-modal-buttons');
        modalMsg.textContent = msg;
        modalBtns.innerHTML = '';
        const ok = document.createElement('button');
        ok.className = 'gui-modal-btn ok';
        ok.textContent = 'OK';
        ok.onclick = () => { backdrop.style.display = 'none'; resolve(); };
        modalBtns.appendChild(ok);
        backdrop.style.display = 'flex';
        setTimeout(() => ok.focus(), 50);
    });
}
// Toast notification
function guiToast(msg, duration = 2200) {
    const toast = document.getElementById('gui-toast');
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), duration);
}

function guiConfirmWithPassword(msg) {
    return new Promise((resolve) => {
        const backdrop = document.getElementById('modal-backdrop');
        const modalMsg = document.getElementById('gui-modal-message');
        const modalBtns = document.getElementById('gui-modal-buttons');
        modalMsg.innerHTML = `<div style="margin-bottom:1em">${msg}</div><input id="confirm-password-input" type="password" placeholder="Password" style="width:90%;padding:0.5em;border-radius:5px;border:1px solid #31313a;background:#232329;color:#f3f3f5;">`;
        modalBtns.innerHTML = '';
        const ok = document.createElement('button');
        ok.className = 'gui-modal-btn ok';
        ok.textContent = 'OK';
        ok.onclick = () => {
            const pw = document.getElementById('confirm-password-input').value;
            backdrop.style.display = 'none';
            resolve({confirmed: true, password: pw});
        };
        const cancel = document.createElement('button');
        cancel.className = 'gui-modal-btn cancel';
        cancel.textContent = 'Cancel';
        cancel.onclick = () => { backdrop.style.display = 'none'; resolve({confirmed: false, password: null}); };
        modalBtns.appendChild(ok);
        modalBtns.appendChild(cancel);
        backdrop.style.display = 'flex';
        setTimeout(() => document.getElementById('confirm-password-input').focus(), 50);

        // ESC cancels, ENTER accepts
        backdrop.onkeydown = (e) => {
            if (e.key === "Escape") { cancel.click(); }
            if (e.key === "Enter") { ok.click(); }
        };
        document.getElementById('confirm-password-input').onkeydown = backdrop.onkeydown;
    });
}

// QoL: Keyboard shortcuts for confirm/alert
function guiConfirm(msg) {
    return new Promise((resolve) => {
        const backdrop = document.getElementById('modal-backdrop');
        const modalMsg = document.getElementById('gui-modal-message');
        const modalBtns = document.getElementById('gui-modal-buttons');
        modalMsg.textContent = msg;
        modalBtns.innerHTML = '';
        const ok = document.createElement('button');
        ok.className = 'gui-modal-btn ok';
        ok.textContent = 'OK';
        ok.onclick = () => { backdrop.style.display = 'none'; resolve(true); };
        const cancel = document.createElement('button');
        cancel.className = 'gui-modal-btn cancel';
        cancel.textContent = 'Cancel';
        cancel.onclick = () => { backdrop.style.display = 'none'; resolve(false); };
        modalBtns.appendChild(ok);
        modalBtns.appendChild(cancel);
        backdrop.style.display = 'flex';
        setTimeout(() => ok.focus(), 50);
        // Keyboard shortcuts
        backdrop.onkeydown = (e) => {
            if (e.key === "Escape") { cancel.click(); }
            if (e.key === "Enter") { ok.click(); }
        };
        ok.onkeydown = backdrop.onkeydown;
        cancel.onkeydown = backdrop.onkeydown;
        backdrop.focus();
    });
}
function guiAlert(msg) {
    return new Promise((resolve) => {
        const backdrop = document.getElementById('modal-backdrop');
        const modalMsg = document.getElementById('gui-modal-message');
        const modalBtns = document.getElementById('gui-modal-buttons');
        modalMsg.textContent = msg;
        modalBtns.innerHTML = '';
        const ok = document.createElement('button');
        ok.className = 'gui-modal-btn ok';
        ok.textContent = 'OK';
        ok.onclick = () => { backdrop.style.display = 'none'; resolve(); };
        modalBtns.appendChild(ok);
        backdrop.style.display = 'flex';
        setTimeout(() => ok.focus(), 50);
        backdrop.onkeydown = (e) => {
            if (e.key === "Escape" || e.key === "Enter") { ok.click(); }
        };
        ok.onkeydown = backdrop.onkeydown;
        backdrop.focus();
    });
}
