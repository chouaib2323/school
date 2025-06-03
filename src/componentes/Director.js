import React from 'react'

function Director(props) {
  return (
    <div>
<div class="max-w-md mx-auto mb-4 ">
    <div class="bg-white p-4 shadow-md rounded-lg flex flex-col justify-center">
        <h3 class="font-bold text-xl mb-2">{props.name}</h3>
        <img src={props.src} class="mb-2 rounded-lg max-h-56" alt="Book Cover" />
        <h3 class="font-bold text-xl mb-2">email : <i className=' font-semibold'>{props.email}</i> </h3>
        <h3 class="font-bold text-xl mb-2">modules : <i  className=' font-semibold'>{props.modules}</i></h3>
        <p class="text-gray-700"><i className=' font-bold'>Biography</i> :{props.bio} </p>
    </div>
</div>

    </div>
  )
}

export default Director