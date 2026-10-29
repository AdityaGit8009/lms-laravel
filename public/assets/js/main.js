/* EDUCORE LMS — main.js */
var DEFAULTS = {
    theme: "dark",
    accent: "teal",
    radius: "medium",
    sidebar: "expanded",
};

document.addEventListener("DOMContentLoaded", function () {
    var cfg = loadSettings();
    applySettings(cfg);
    syncUI();
});

/* ── STORAGE ── */
function loadSettings() {
    try {
        return JSON.parse(localStorage.getItem("ec_cfg") || "{}");
    } catch (e) {
        return {};
    }
}
function saveSettings(cfg) {
    try {
        localStorage.setItem("ec_cfg", JSON.stringify(cfg));
    } catch (e) {}
}

/* ── APPLY ── */
function applySettings(cfg) {
    var s = Object.assign({}, DEFAULTS, cfg);
    document.documentElement.setAttribute("data-theme", s.theme);
    document.documentElement.setAttribute("data-accent", s.accent);
    document.documentElement.setAttribute("data-radius", s.radius);
    document.documentElement.setAttribute("data-sidebar", s.sidebar);
}

/* ── SYNC UI BUTTONS ── */
function syncUI() {
    var t = document.documentElement.getAttribute("data-theme") || "dark";
    var a = document.documentElement.getAttribute("data-accent") || "teal";
    var r = document.documentElement.getAttribute("data-radius") || "medium";
    var s = document.documentElement.getAttribute("data-sidebar") || "expanded";
    // mode btns
    document.querySelectorAll("[data-mode-btn]").forEach(function (b) {
        b.classList.toggle("active", b.getAttribute("data-mode-btn") === t);
    });
    // swatches
    document.querySelectorAll("[data-swatch]").forEach(function (b) {
        b.classList.toggle("active", b.getAttribute("data-swatch") === a);
    });
    // radius
    document.querySelectorAll("[data-radius-btn]").forEach(function (b) {
        b.classList.toggle("active", b.getAttribute("data-radius-btn") === r);
    });
    // sidebar
    document.querySelectorAll("[data-sidebar-btn]").forEach(function (b) {
        b.classList.toggle("active", b.getAttribute("data-sidebar-btn") === s);
    });
    // mode icon
    var mi = document.querySelector("#topModeBtn i");
    if (mi)
        mi.className =
            t === "dark" ? "bi bi-moon-stars-fill" : "bi bi-sun-fill";
}

/* ── MODE ── */
function setMode(m) {
    var c = loadSettings();
    c.theme = m;
    saveSettings(c);
    document.documentElement.setAttribute("data-theme", m);
    syncUI();
}
function quickMode() {
    setMode(
        document.documentElement.getAttribute("data-theme") === "dark"
            ? "light"
            : "dark",
    );
}

/* ── ACCENT ── */
function setAccent(a) {
    var c = loadSettings();
    c.accent = a;
    saveSettings(c);
    document.documentElement.setAttribute("data-accent", a);
    syncUI();
    if (typeof rebuildCharts === "function") setTimeout(rebuildCharts, 80);
}

/* ── RADIUS ── */
function setRadius(r) {
    var c = loadSettings();
    c.radius = r;
    saveSettings(c);
    document.documentElement.setAttribute("data-radius", r);
    syncUI();
}

/* ── SIDEBAR ── */
function setSidebar(m) {
    var c = loadSettings();
    c.sidebar = m;
    saveSettings(c);
    document.documentElement.setAttribute("data-sidebar", m);
    syncUI();
}
function toggleCollapse() {
    setSidebar(
        document.documentElement.getAttribute("data-sidebar") === "collapsed"
            ? "expanded"
            : "collapsed",
    );
}
function toggleMob() {
    document.getElementById("sidebar").classList.toggle("mob-open");
}

/* ── SHADOWS ── */
function toggleShadows(el) {
    el.classList.toggle("on");
    var v = el.classList.contains("on") ? "" : "none";
    document.querySelectorAll(".stat-card,.card").forEach(function (c) {
        c.style.setProperty("--shadow", v);
    });
}

/* ── SETTINGS PANEL ── */
function openSettings() {
    document.getElementById("settingsPanel").classList.add("open");
    document.getElementById("settingsOverlay").classList.add("open");
}
function closeSettings() {
    document.getElementById("settingsPanel").classList.remove("open");
    document.getElementById("settingsOverlay").classList.remove("open");
}

/* ── NOTIFICATIONS ── */
function toggleNotif() {
    document.getElementById("notifPanel").classList.toggle("open");
}

/* ── FULLSCREEN ── */
function toggleFS() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(function () {});
    } else {
        document.exitFullscreen();
    }
}
document.addEventListener("fullscreenchange", function () {
    var fs = !!document.fullscreenElement;
    document.querySelectorAll(".fs-icon").forEach(function (i) {
        i.className =
            (fs ? "bi bi-fullscreen-exit" : "bi bi-arrows-fullscreen") +
            " fs-icon";
    });
    document.querySelectorAll(".fs-label").forEach(function (l) {
        l.textContent = fs ? "Exit Fullscreen" : "Enter Fullscreen";
    });
});

/* ── RESET ── */
function resetAll() {
    localStorage.removeItem("ec_cfg");
    applySettings(DEFAULTS);
    syncUI();
    if (typeof rebuildCharts === "function") setTimeout(rebuildCharts, 80);
    showToast("Reset to default", "info");
}

/* ── COUNTER ── */
function animCount(id, to, pre, suf, dur) {
    pre = pre || "";
    suf = suf || "";
    dur = dur || 1300;
    var el = document.getElementById(id);
    if (!el) return;
    var s = Date.now();
    (function tick() {
        var p = Math.min((Date.now() - s) / dur, 1),
            e = 1 - Math.pow(1 - p, 3);
        el.textContent = pre + Math.floor(e * to).toLocaleString() + suf;
        if (p < 1) requestAnimationFrame(tick);
    })();
}

/* ── TOAST ── */
function showToast(msg, type, dur) {
    type = type || "info";
    dur = dur || 3200;
    var icons = {
        success: "bi-check-circle-fill",
        error: "bi-x-circle-fill",
        info: "bi-info-circle-fill",
        warning: "bi-exclamation-triangle-fill",
    };
    var colors = {
        success: "#10b981",
        error: "#f43f5e",
        info: "var(--accent)",
        warning: "#f59e0b",
    };
    var t = document.createElement("div");
    t.className = "toast " + type;
    t.innerHTML =
        '<i class="bi ' +
        icons[type] +
        '" style="color:' +
        colors[type] +
        ';font-size:1.1rem;flex-shrink:0"></i><span style="flex:1">' +
        msg +
        '</span><i class="bi bi-x" style="cursor:pointer;color:var(--muted);flex-shrink:0" onclick="this.parentElement.remove()"></i>';
    var c = document.getElementById("toastContainer");
    if (c) c.appendChild(t);
    setTimeout(function () {
        if (t.parentElement) {
            t.style.opacity = "0";
            t.style.transition = "opacity .3s";
            setTimeout(function () {
                t.remove();
            }, 300);
        }
    }, dur);
}

/* ── MODAL HELPERS ── */
function openModal(id) {
    new bootstrap.Modal(document.getElementById(id)).show();
}
function closeModal(id) {
    var m = bootstrap.Modal.getInstance(document.getElementById(id));
    if (m) m.hide();
}

/* ── DATATABLE INIT — all 6 buttons ── */
function initDataTable(tableId, extra) {
    var opts = $.extend(
        {
            dom: '<"d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3"<"d-flex gap-2 flex-wrap"B><"d-flex gap-2 align-items-center"lf>>rt<"d-flex align-items-center justify-content-between mt-2"ip>',
            buttons: [
                {
                    extend: "copy",
                    text: '<i class="bi bi-clipboard me-1"></i>Copy',
                    className: "dt-button buttons-copy",
                },
                {
                    extend: "csv",
                    text: '<i class="bi bi-filetype-csv me-1"></i>CSV',
                    className: "dt-button buttons-csv",
                },
                {
                    extend: "excel",
                    text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                    className: "dt-button buttons-excel",
                },
                {
                    extend: "pdf",
                    text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                    className: "dt-button buttons-pdf",
                },
                {
                    extend: "print",
                    text: '<i class="bi bi-printer me-1"></i>Print',
                    className: "dt-button buttons-print",
                },
                {
                    extend: "colvis",
                    text: '<i class="bi bi-layout-three-columns me-1"></i>Columns',
                    className: "dt-button buttons-colvis",
                },
            ],
            pageLength: 10,
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_",
                info: "_START_–_END_ of _TOTAL_",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                },
            },
            responsive: true,
        },
        extra || {},
    );
    return $("#" + tableId).DataTable(opts);
}

document.addEventListener("DOMContentLoaded", function () {
    const photoInput = document.getElementById("profilePhotoInput");
    const photoForm = document.getElementById("photoUploadForm");

    if (photoInput && photoForm) {
        photoInput.addEventListener("change", function () {
            if (this.files && this.files.length > 0) {
                photoForm.submit();
            }
        });
    }
});
