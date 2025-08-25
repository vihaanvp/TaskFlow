document.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('lists-nav')) return; // only run on dashboard

    let currentListId = null;

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
                        // List link
                        const a = document.createElement('a');
                        a.className = 'list-link' + ((selectId ? list.id === selectId : idx === 0) ? ' active' : '');
                        a.textContent = list.title;
                        a.href = '#';
                        a.dataset.id = list.id;
                        a.onclick = function(e) {
                            e.preventDefault();
                            document.querySelectorAll('.list-link').forEach(l => l.classList.remove('active'));
                            this.classList.add('active');
                            loadItems(list.id, list.title);
                            currentListId = list.id;
                        };
                        wrap.appendChild(a);
                        // Delete button
                        const btn = document.createElement('button');
                        btn.className = 'delete-list-btn';
                        btn.textContent = '🗑️';
                        btn.title = 'Delete List';
                        btn.dataset.id = list.id;
                        btn.onclick = function(e) {
                            e.stopPropagation();
                            guiConfirm('Delete this list and all its tasks?').then(confirmed => {
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
                        wrap.appendChild(btn);
                        nav.appendChild(wrap);
                    });
                    // Select first or newly created
                    let sel = selectId ? selectId : data.lists[0].id;
                    let selTitle = data.lists.find(l => l.id == sel).title;
                    currentListId = sel;
                    loadItems(sel, selTitle);
                }
            });
    }

    // Add New List
    document.getElementById('add-list-form').onsubmit = function(e) {
        e.preventDefault();
        const title = document.getElementById('new-list-title').value.trim();
        if (!title) return;
        fetch('api/lists.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'add', title}),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('new-list-title').value = '';
                    loadLists(data.id);
                    guiToast('List added!');
                }
            });
    };

    // Load items for a list
    function loadItems(listId, listTitle) {
        fetch('api/items.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({action: 'all', list_id: listId}),
        })
            .then(r => r.json())
            .then(data => {
                let html = `<h2 class="list-title">${listTitle}</h2>`;
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
                <button type="submit">Add</button>
            </form>`;
                document.getElementById('main-content').innerHTML = html;

                // Add Item
                document.getElementById('add-item-form').onsubmit = function(e) {
                    e.preventDefault();
                    const content = document.getElementById('new-item-content').value.trim();
                    if (!content) return;
                    fetch('api/items.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({action: 'add', list_id: listId, content}),
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                loadItems(listId, listTitle);
                                guiToast('Task added!');
                            }
                        });
                };

                // Toggle and Delete
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
                            loadItems(listId, listTitle);
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
                                    loadItems(listId, listTitle);
                                    guiToast('Task deleted!');
                                });
                            }
                        });
                    };
                });
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
