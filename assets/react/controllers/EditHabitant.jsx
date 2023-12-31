import React, { useEffect, useState } from "react";
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import logo from '../../../assets/img/Logomairie.png';


export default function AddHabitant(props) {

    useEffect(() => {
        fetchUserData(props.uid_habitant)
    }, [])

    const fetchUserData = (uid) => {
        fetch("/habitant/get/" + uid)
            .then(response => {
                if (response.status == 404) {
                    toast.error("Habitant introuvable !", {
                        position: toast.POSITION.TOP_CENTER
                    });
                }

                return response.json()
            })
            .then(data => {
                console.log(data)
                setNom(data.nom);
                setPrenom(data.prenom);
                setDateNaissance(data.dateNaissance);
                setGenre(data.genre);
                setNumero_address(data.numero_address);
                setRue_address(data.rue_address);
            })
    }


    const [Nom, setNom] = React.useState('');
    const [Prenom, setPrenom] = React.useState('');
    const [dateNaissance, setDateNaissance] = React.useState('');
    const [genre, setGenre] = React.useState('');
    const [numero_address, setNumero_address] = React.useState('');
    const [rue_address, setRue_address] = React.useState('');


    const handleUpdate = (event) => {
        event.preventDefault();
        console.log({ Nom, Prenom, dateNaissance, genre });
        axios.put('/habitant/update/' + props.uid_habitant,
            {
                "nom": Nom || "",
                "prenom": Prenom || "",
                "date_naissance": dateNaissance || "",
                "genre": genre || "",
                "numero_address": numero_address || "",
                "rue_address": rue_address || ""
            })

            .then(function (response) {
                if (response.status == 200) {
                    toast.success("Habitant modifier avec succès !", {
                        position: toast.POSITION.TOP_CENTER
                    });
                } else if (response.status == 208) {
                    toast.error("Erreur :( !", {
                        position: toast.POSITION.TOP_CENTER
                    });
                }
            })
            .catch(function (error) {
                console.log(error);
            });
    };

    const habitantDelete = (event) => {
        event.preventDefault();
        console.log({ Nom, Prenom, dateNaissance, genre });
        axios.delete('/habitant/delete/' + props.uid_habitant)
            .then(function (response) {
                if (response.status == 200) {
                    window.location.replace("/recensement");
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
        <div className='flex flex-col place-content-evenly items-center	'>
            <ToastContainer />
            <div className="flex flex-row items-center place-items-center">
                <img className="h-36" src={logo}></img>

                <h1 className='text-3xl font-bold p-2 content-center'>Ajouter un habitant</h1>

            </div>

            <form className='place-content-around flex flex-col p-4' onSubmit={handleUpdate}>
                <div className="p-2">
                    <h2 className='text-xl font-bold'>Habitant :</h2>
                </div>
                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Nom :
                    </label>

                    <input className="border-black border-2 grow" type="text" value={Nom} onChange={(e) => setNom(e.target.value)} />

                </div>
                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Prénom :
                    </label>
                    <input className="border-black border-2 grow" type="text" value={Prenom} onChange={(e) => setPrenom(e.target.value)} />

                </div>
                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Date de Naissance :
                    </label>
                    <input className="border-black border-2 grow" type="date" value={dateNaissance} onChange={(e) => setDateNaissance(e.target.value)} />

                </div>
                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Genre :
                    </label>
                    <select className="border-black border-2" value={genre} onChange={(e) => setGenre(e.target.value)}>
                        <option value="">Select...</option>
                        <option value="homme">Homme</option>
                        <option value="femme">Femme</option>
                        <option value="non_binaire">Non Binaire</option>
                        <option value="autre">Autre</option>
                    </select>

                </div>
                <div className="p-2 flex flex-row">
                    <h2 className='text-xl font-bold'>Addresse :</h2>
                </div>

                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Numéro de rue :
                    </label>
                    <input className="border-black border-2 grow" type="text" value={numero_address} onChange={(e) => setNumero_address(e.target.value)} />

                </div>

                <div className="p-2 flex flex-row">
                    <label className="mx-3">
                        Nom de rue :
                    </label>
                    <input className="border-black border-2 grow" type="text" value={rue_address} onChange={(e) => setRue_address(e.target.value)} />

                </div>


                <div className="my-5 flex flex-row place-content-evenly">

                    <a href="/recensement">
                        <div className="text-xl font-bold bg-slate-400 p-2 rounded-md hover:scale-110  duration-300 delay-75 ease-in-out shadow">Retour</div>
                    </a>
                    <button type="submit" className="text-xl font-bold bg-slate-400 p-2 rounded-md hover:scale-110  duration-300 delay-75 ease-in-out shadow">Modifier</button>

                    <button onClick={habitantDelete} className="text-xl font-bold bg-red-600 p-2 rounded-md hover:scale-110  duration-300 delay-75 ease-in-out shadow">Supprimer</button>


                </div>
            </form>


        </div>
    );
}
