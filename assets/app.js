const translations = {
  lo:{
    search:"ຄົ້ນຫາ...",eventSearch:"ຄົ້ນຫາງານຕະຫຼາດນັດ... (ຊື່ງານ, ສະຖານທີ່, ປະເພດ)",
    login:"ເຂົ້າສູ່ລະບົບ",register:"ສະໝັກສະມາຊິກ",home:"ໜ້າຫຼັກ",events:"ງານຕະຫຼາດນັດ",locations:"ສະຖານທີ່",categories:"ປະເພດງານ",organizers:"ຜູ້ຈັດງານ",news:"ຂ່າວສານ/ບົດຄວາມ",contact:"ຕິດຕໍ່",
    hero1:"ຮວບຮວມງານຕະຫຼາດນັດ",hero2:"ໃນທຸກທົ່ວປະເທດລາວ",heroDesc:"ຄົ້ນຫາງານຕະຫຼາດນັດ ໃຫ້ບ້ານທ່ານໄດ້ງ່າຍ ແລະ ວ່ອງໄວ ອັບເດດຂ່າວສານ ແລະ ກິດຈະກຳທີ່ໜ້າສົນໃຈທຸກມື້",
    allRegions:"ທຸກພາກ",searchBtn:"ຄົ້ນຫາ",categoryTitle:"ປະເພດງານຕະຫຼາດນັດ",recommended:"ງານຕະຫຼາດນັດແນະນຳ",viewAll:"ເບິ່ງທັງໝົດ",noEvents:"ບໍ່ພົບງານຕະຫຼາດນັດ",tryAgain:"ລອງປ່ຽນຄຳຄົ້ນຫາ ຫຼື ຕົວກອງ",
    organizerQuestion:"ທ່ານເປັນຜູ້ຈັດງານບໍ?",organizerDesc:"ລົງທະບຽນງານຕະຫຼາດນັດຂອງທ່ານ ໃຫ້ຄົນຮູ້ຈັກງ່າຍຂຶ້ນ",freeRegister:"ລົງທະບຽນງານຟຣີ",users:"ຜູ້ໃຊ້",
    footerDesc:"ເວັບໄຊລວບລວມງານຕະຫຼາດນັດໃນປະເທດລາວ ຊ່ວຍໃຫ້ທ່ານຄົ້ນຫາກິດຈະກຳໄດ້ງ່າຍຂຶ້ນ",quickMenu:"ເມນູດ່ວນ",support:"ສະໜັບສະໜູນ",faq:"ຄຳຖາມທີ່ພົບເລື້ອຍ",guide:"ຄູ່ມືການໃຊ້ງານ",privacy:"ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ"
  },
  zh:{
    search:"搜索...",eventSearch:"搜索集市活动...（名称、地点、类别）",login:"登录",register:"注册",home:"首页",events:"集市活动",locations:"地点",categories:"活动类别",organizers:"主办方",news:"新闻/文章",contact:"联系我们",
    hero1:"汇集老挝各地集市活动",hero2:"发现老挝精彩活动",heroDesc:"轻松快速地查找附近的集市活动，每天更新活动资讯和精彩内容",allRegions:"全部地区",searchBtn:"搜索",categoryTitle:"集市活动类别",recommended:"推荐活动",viewAll:"查看全部",noEvents:"没有找到活动",tryAgain:"请更换搜索关键词或筛选条件",
    organizerQuestion:"您是活动主办方吗？",organizerDesc:"免费发布您的集市活动，让更多人看到",freeRegister:"免费发布活动",users:"用户",footerDesc:"老挝集市活动信息平台，帮助您轻松发现精彩活动",quickMenu:"快速菜单",support:"帮助支持",faq:"常见问题",guide:"使用指南",privacy:"隐私政策"
  },
  en:{
    search:"Search...",eventSearch:"Search market events... (name, location, category)",login:"Login",register:"Register",home:"Home",events:"Market Events",locations:"Locations",categories:"Categories",organizers:"Organizers",news:"News / Articles",contact:"Contact",
    hero1:"Discover market events",hero2:"across Laos",heroDesc:"Find local market events quickly and easily, with updated activities and event information every day.",allRegions:"All regions",searchBtn:"Search",categoryTitle:"Market categories",recommended:"Recommended events",viewAll:"View all",noEvents:"No market events found",tryAgain:"Try changing your search or filters",
    organizerQuestion:"Are you an event organizer?",organizerDesc:"Register your market event and let more people discover it.",freeRegister:"Register for free",users:"Users",footerDesc:"A Laos market-event platform that makes it easy to discover local activities.",quickMenu:"Quick menu",support:"Support",faq:"FAQ",guide:"User guide",privacy:"Privacy policy"
  }
};

function applyLanguage(lang){
  const t=translations[lang]||translations.lo;
  document.documentElement.lang=lang;
  document.querySelectorAll("[data-i18n]").forEach(el=>{
    const key=el.dataset.i18n;
    if(t[key]) el.textContent=t[key];
  });
  document.querySelectorAll("[data-i18n-placeholder]").forEach(el=>{
    const key=el.dataset.i18nPlaceholder;
    if(t[key]) el.placeholder=t[key];
  });
  document.getElementById("langText").textContent={lo:"ລາວ",zh:"中文",en:"English"}[lang];
  localStorage.setItem("lem_lang",lang);
}
const langBtn=document.getElementById("langBtn"), langMenu=document.getElementById("langMenu");
if(langBtn){
  langBtn.addEventListener("click",()=>langMenu.classList.toggle("show"));
  document.addEventListener("click",e=>{if(!e.target.closest(".lang-wrap")) langMenu.classList.remove("show")});
  document.querySelectorAll("#langMenu button").forEach(b=>b.addEventListener("click",()=>applyLanguage(b.dataset.lang)));
  applyLanguage(localStorage.getItem("lem_lang")||"lo");
}
document.querySelectorAll(".heart").forEach(btn=>{
  btn.addEventListener("click",e=>{
    e.preventDefault();
    e.stopPropagation();
    btn.classList.toggle("active");
    const icon=btn.querySelector("i");
    if(icon) icon.className=btn.classList.contains("active")?"ti ti-heart-filled":"ti ti-heart";
  });
});
