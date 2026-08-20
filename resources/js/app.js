import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';
import collapse from '@alpinejs/collapse';
import Dexie from 'dexie';
import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Dexie = Dexie;
window.Chart = Chart;

// Initialize Offline IndexedDB via Dexie
const db = new Dexie('SmartPOS_OfflineDB');
db.version(1).stores({
    ordersQueue: '++id, uuid, order_data, created_at, synced',
    cachedCatalog: 'key, data, updated_at'
});
window.posDB = db;

// Helper to render Lucide icons dynamically
window.initLucideIcons = () => {
    createIcons({ icons });
};

Alpine.plugin(mask);
Alpine.plugin(collapse);

document.addEventListener('DOMContentLoaded', () => {
    window.initLucideIcons();
});

Alpine.start();
