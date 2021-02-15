// Start open popup To Upload Image
let btnEditImg = document.querySelector("#btnEditImg");
if (btnEditImg) {
    btnEditImg.addEventListener('click',function () {
        $(".pop-box-edit-img").css('display', 'block',function () {
            $(this).fadeIn();
        });
    });
}
function changeDom() {
    let vues = document.querySelectorAll(".pop-box-edit-img .vues div");
    let vuesArr = Array.from(vues);
    vuesArr.find(item => item.getAttribute("data-show") == localStorage.getItem('btnActiveImg')).classList.add("active");
    vuesArr.find(item => item.getAttribute("data-show") !== localStorage.getItem('btnActiveImg')).classList.remove("active");
}
let options = document.querySelectorAll(".pop-box-edit-img .options p.lead");
let optionsArr = Array.from(options);
options.forEach(item => {
    item.addEventListener('click', (e) => {
        localStorage.setItem('btnActiveImg',e.target.dataset.vue);
        optionsArr.find(item => item.getAttribute("data-vue") == localStorage.getItem('btnActiveImg')).classList.add("active");
        optionsArr.find(item => item.getAttribute("data-vue") !== localStorage.getItem('btnActiveImg')).classList.remove("active");
        changeDom();
    });
});
$(".pop-box-edit-img .remove").click(function () {
    $(".pop-box-edit-img").hide();
});
let hiddenAfter5 = document.querySelectorAll('.hidden-after-5');
hiddenAfter5.forEach(e => {
    setTimeout(() => {
        e.style.display = 'none';
    },5000);
});