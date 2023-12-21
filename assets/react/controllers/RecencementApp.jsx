import React from "react";
import logo from '../../../assets/img/Logomairie.png';

export default function RecencementApp() {

    return (
        <div className="flex flex-col place-content-evenly items-center h-screen">


            <div className="flex flex-row items-center place-items-center">

                <img className="h-36" src={logo}></img>

                <h1 className="text-3xl font-bold p-4">Recensement de la population</h1>

            </div>

            <div className="flex flex-row items-center place-items-center grow ">

                <a href="/recensement/ajout_habitant">

                    <button className="relative rounded-lg bg-gray-400 place-content-center hover:scale-110  duration-300 delay-75 ease-in-out shadow m-4 ">
                        <h1 className="text-2xl font-semibold p-2 m-4">
                            Ajouter un Habitant
                        </h1>
                    </button>
                </a>
                <a href="/recensement/list">

                    <button className="relative rounded-lg bg-gray-400 place-content-center hover:scale-110  duration-300 delay-75 ease-in-out shadow m-4 ">
                        <h1 className="text-2xl font-semibold p-2 m-4">
                            Liste des Habitants
                        </h1>
                    </button>
                </a>

            </div>



        </div>
    )

}