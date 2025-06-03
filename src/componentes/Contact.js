import React, { useState } from 'react'
import Footer from './Footer'
import Navbar from './Navbar'
function Contact() {
  const [data,setData]=useState({
    nom:'',
    email:'',
    message:''
  })
  const handleSubmit = async (event) => {
    event.preventDefault();
    try {
        const response = await fetch('https://localhost/school/mess.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            console.log("data sent");
           alert("your message is sent")
        } else {
            console.log("data not sent");
        }
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};
  return (
    <div>
        <Navbar/>

        <section className='  flex items-center justify-center md:h-auto h-screen'>
          
        
        <div className="border-2 bg-orange-200 py-7 my-10 w-5/6 rounded-md">
        <p className=' font-bold p-6 underline text-xl text-center'>  رسالتك  </p>
    <form className="grid place-items-center justify-center space-y-10" onSubmit={handleSubmit}>
      <table>
        <tbody>
          <tr>
            <td><label className="font-bold" htmlFor="name"> الاسم و اللقب : </label></td>
            <td><input onChange={(e)=>{setData(prevData => ({  ...prevData, nom: e.target.value }))}} className="border-2 border-orange-300" type="text" id="name" name="" /></td>
          </tr>
          <tr>
            <td><label className="font-bold" htmlFor="email"> email :  </label></td>
            <td><input onChange={(e)=>{setData(prevData => ({  ...prevData, email: e.target.value }))}} className="border-2 border-orange-300" type="text" id="email" /></td>
          </tr>
          <tr>
            <td><label className="font-bold" htmlFor=""> رسالتك : </label></td>
            <td><textarea onChange={(e)=>{setData(prevData => ({  ...prevData, message: e.target.value }))}} className="border-2 border-orange-300"></textarea></td>
          </tr>
        </tbody>
      </table>
      <button className="border-2 border-orange-500 bg-orange-300 font-bold px-10 py-4 text-white rounded-md" type="submit">submit</button>
    </form>
  </div>
        </section>
        <Footer/>
    </div>
  )
}
export default Contact