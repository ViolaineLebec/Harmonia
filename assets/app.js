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

// document.addEventListener('click', async (event) => {
//     const btn = event.target.closest('.bouton');
//     if (!btn) return;
//
//     event.preventDefault();
//
//     try {
//         const res = await fetch(btn.dataset.url, { method: 'POST' });
//         if (res.ok) {
//             const data = await res.json();
//             btn.textContent = data.label;
//         }
//     } catch (error) {
//         console.error('Erreur :', error);
//     }
// });

// Jules :
//on récupere le bouton par un id
const btn = document.querySelector("#btn-add-fav");

//on veux vérifier si le bouton est pas null avant d'executer le code ci dessous
if (btn !== null) {
    //je récupère son id (défini dans l'html, voir ici : templates/track/track.html.twig)
    const id = btn.getAttribute('data-btn-id');

    //j'ajoute un evenement au click sur le boutton
    btn.addEventListener('click', () => {

        //au moment ou je click je vais intéroger mon controller qui a la rouute /handle-favorite/{id}'
        fetch('/handle-favorite/' + id).then((response) => {
            return response.json();
        }).then((data) => {
            //ici dans data => c'est le json que j'envoi coté controller
            console.log(data);

            //je check data.isCreated que je renvoi depuis le controller en json
            // si c'est true => je passe le label du bouton à : Supprimer le favoris
            if(data.isCreated === true){
                btn.textContent = "Supprimer le favoris"
            } else {
                // ici c'est donc false => je passe le label du bouton à : Ajouter aux favoris
                btn.textContent = "Ajouter aux favoris"
            }
        })
    })
}