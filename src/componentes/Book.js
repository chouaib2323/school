import React from 'react'

function Book(props) {


  return (
    <div class="max-w-md mx-auto pb-4 ">
    <div class="bg-white p-4 shadow-md rounded-lg  ">
        <h3 class="font-bold text-xl mb-2">{props.title}</h3>
        <img src={props.src} class=" pb-2 rounded-lg w-auto h-80" alt="Book Cover" />
        <a href={props.download} class="text-blue-500 hover:underline block mb-2">Download Book</a>
        <p class="text-gray-700">Author: {props.name}</p>
    </div>
</div>

  )
}

export default Book