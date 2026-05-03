import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import focus from '@alpinejs/focus';

Alpine.plugin(persist);
Alpine.plugin(focus);
window.Alpine = Alpine;
Alpine.start();
