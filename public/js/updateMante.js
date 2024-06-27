const summaryElements = document.querySelectorAll('summary');
const downArrows = document.querySelectorAll('.iconoBajar');
const upArrows = document.querySelectorAll('.iconoSubir');

summaryElements.forEach((summaryElement, index) => {
    const downArrow = downArrows[index];
    const upArrow = upArrows[index];
    let isOpen = false; // Inicialmente cerrado

    summaryElement.addEventListener('click', () => {
        isOpen = !isOpen;

        if (isOpen) {
            downArrow.style.display = 'none';
            upArrow.style.display = 'inline';
        } else {
            downArrow.style.display = 'inline';
            upArrow.style.display = 'none';
        }
    });
});
const openModal = document.getElementById('abrir')
const modal = document.querySelector('.modalDad');
openModal.addEventListener('click', () => {
    modal.style.animationName='modalDad';
    modal.style.animationDuration='1s'
modal.classList.add('modal--show');

})
document.querySelector('.close').addEventListener('click',()=>{
    modal.style.animationName='modalClose';
    modal.style.animationDuration='1s'
    modal.classList.remove('modal--show');
})
window.addEventListener('click',function (e) {
    if (e.target == modal) {
        modal.style.animationName='modalClose';
        modal.style.animationDuration='1s'
        modal.classList.remove('modal--show');
    }
    
})
document.getElementById('volverMant').addEventListener('click',()=>{
    window.location.href = mantenimiento;
  })