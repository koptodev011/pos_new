/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/**/*.blade.php",
    "./resources/**/**/*.js",
    "./app/View/Components/**/**/*.php",
    "./app/Livewire/**/**/*.php",
  ],
  theme: {
    extend: {
      colors: {

        
        'primary': '#1B202A',
        'secondary': '#272B32',
        'primaryblue': '#0CB3E0',
        'backgroundblue': 'rgba(12, 179, 224, 0.2)',
        'blackgrey':'#31363E',
        'discription':'#8D99AE',
        'redheart':'#FB5607',
        'gr':'#0F4362',
        'gr2':'  #192F3C',
        'dotline':'#0CB3E026',
        'preparing':'#FDB91F',
        'bordercol':' #0CB3E01A',
        'greensucc':'#25D375',
        'textfield':'rgba(0, 0, 0, 0.2)'
      },
      fontFamily: {
        sans: ['Urbanist', 'sans-serif',],
      },
      height: {
        '30': '150px',
        '120': '105px',
        '90': '200px',
        
      },
      width: {
        '30': '150px',
        '18':'70px'
      },
      margin: {
        '85': '85px', 
      },
    },
    plugins: [],
  }
}