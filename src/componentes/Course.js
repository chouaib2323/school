import React from 'react'

function Course(props) {


  return (
<div class="max-w-md mx-auto mb-4 ">
    <div class="bg-white p-4 shadow-md rounded-lg">
        <h3 class="font-bold text-xl mb-2">{props.title}</h3>
        <h3 class="font-bold text-md mb-2">level: {props.level}</h3>
        <a href={props.download} class="text-blue-500 hover:underline block mb-2">Download Course</a>
        <h1 class="text-gray-700 font-semibold">Module: {props.name}</h1>
        <p class="text-gray-700 overflow-y-scroll max-h-28">Description: {props.description}</p>
    </div>
</div>
  )
}

export default Course