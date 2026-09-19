import './stimulus_bootstrap.js';
// import 
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// let btn = document.querySelector("#btn");
// let id = btn.getAttribute('data-id');
// btn.addEventListener("click", handleFavorite());

// document.addEventListener('DOMContentLoaded', () => {
//     const btn = document.querySelector('.btn');
//     if (btn) {
//         btn.onclick = async function maFonction() {
//             const res = await fetch(this.dataset.url, { method: 'POST' });
//             const data = await res.json();
//             this.textContent = data.label;
//         }
//     }
// });

document.addEventListener('click', async (event) => {
    const btn = event.target.closest('.bouton');
    if (!btn) return;

    event.preventDefault();

    try {
        const res = await fetch(btn.dataset.url, { method: 'POST' });
        if (res.ok) {
            const data = await res.json();
            btn.textContent = data.label;
        }
    } catch (error) {
        console.error('Erreur :', error);
    }
});