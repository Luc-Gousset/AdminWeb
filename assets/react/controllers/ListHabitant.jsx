import React, { useState, useEffect } from 'react';
import axios from 'axios';
import logo from '../../../assets/img/Logomairie.png';
import { ToastContainer, toast } from 'react-toastify';

export default function ListHabitant() {
    const [habitants, setHabitants] = useState([]);

    const [searchTerm, setSearchTerm] = useState("");

    const [order, setOrder] = useState("asc");

    const asc = "▲";
    const desc = "▼";
    
    useEffect(() => {
        axios.get('/habitant/get/all')
            .then(response => {
                setHabitants(response.data);
            })
            .catch(error => {
                console.error('There was an error fetching the habitants', error);
            });
    }, []);

    const search = (event) => {
        event.preventDefault();
        axios.get('/habitant/search/', {
            params: {
                term: searchTerm
            }
        })
            .then(response => {
                setHabitants(response.data);
            })
            .catch(error => {
                console.error('There was an error fetching the habitants', error);
            });

    }

    const orderByName = (event) => {
        event.preventDefault();

        if (order == "asc") {
            const nextHab = habitants.sort(function (a, b) {
                if (a.nom.toLowerCase() < b.nom.toLowerCase()) { return -1; }
                if (a.nom.toLowerCase() > b.nom.toLowerCase()) { return 1; }
                return 0;
            });
            setOrder("desc");
        } else {
            const nextHab = habitants.sort(function (a, b) {
                if (a.nom.toLowerCase() < b.nom.toLowerCase()) { return 1; }
                if (a.nom.toLowerCase() > b.nom.toLowerCase()) { return -1; }
                return 0;
            });
            setOrder("asc");
        }
        setHabitants(nextHab);




    };
    const habitantDelete = (event, id) => {
        event.preventDefault();
        axios.delete('/habitant/delete/' + id)
            .then(function (response) {
                if (response.status == 200) {
                    toast.success("Habitant supprimé avec succès !", {
                        position: toast.POSITION.TOP_CENTER
                    });

                    //delete key id from habitants
                    const newHabitants = habitants.filter((habitant) => habitant.id !== id);
                    setHabitants(newHabitants);

                } else if (response.status == 404) {
                    toast.error("Erreur :( !", {
                        position: toast.POSITION.TOP_CENTER
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    };


    return (

        <div className="flex flex-col items-center h-screen">
            <ToastContainer />

            <div className="flex flex-row justify-between mt-4 mb-8 content-center items-center">
                <a href="/recensement">

                    <div className="flex flex-row items-center place-items-center mx-20">
                        <img className="h-28" src={logo} alt="Mairie Logo"></img>
                        <h1 className="text-3xl font-bold pl-4">Recensement de la population</h1>
                    </div>
                </a>
                <form onSubmit={search}>
                    <input id='searchBar' onChange={(e) => setSearchTerm(e.target.value)} className="rounded-lg bg-gray-200 place-content-center shadow m-4 p-2" type="text" placeholder="Rechercher un habitant"></input>

                </form>

                <a href="/recensement/ajout_habitant">

                    <button id='addHabitantButton' className="mx-20 relative rounded-lg bg-gray-400 place-content-center hover:scale-110  duration-300 delay-75 ease-in-out shadow m-4 ">
                        <h1 className="font-semibold p-2">
                            Ajouter un Habitant
                        </h1>
                    </button>
                </a>



            </div>

            <div className="w-full max-w-6xl px-4">
                <div className="grid grid-cols-6 gap-4 mb-4 font-bold sticky top-0 bg-slate-400 my-2">
                <div className="select-none" onClick={event => orderByName(event)}>Nom {order == "asc" ? asc : desc}</div>

                    <div>Prénom</div>
                    <div>Date de Naissance</div>
                    <div>Genre</div>
                    <div>Adresse</div>
                    <div>Actions</div>
                </div>
                {habitants.map(habitant => (
                    <div key={habitant.id} className="grid grid-cols-6 gap-4 items-center p-2 border-b">
                        <div>{habitant.nom}</div>
                        <div>{habitant.prenom}</div>
                        <div>{habitant.dateNaissance}</div>
                        <div>{habitant.genre}</div>
                        <div>{habitant.adresse}</div>
                        <div>
                            <a id={`modify${habitant.id}`} href={`/recensement/edit/${habitant.id}`} className="text-blue-600 hover:text-blue-800 mr-2">Modifier</a>
                            <button id={`buttonDelete${habitant.id}`} onClick={event => habitantDelete(event, habitant.id)} className="text-red-600 hover:text-red-800">Supprimer</button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
