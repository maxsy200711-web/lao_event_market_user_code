(() => {
  const adminPageName = location.pathname.split('/').pop() || '';
  const adminOnlyLao = /^admin(?:_|\.)/.test(adminPageName);
  if (adminOnlyLao) {
    document.documentElement.classList.add('admin-locale-lock');
    const hideAdminPickers = () => document.querySelectorAll('.site-language-picker, .lang-wrap, #langBtn, #langMenu').forEach((node) => node.remove());
    hideAdminPickers();
    new MutationObserver(hideAdminPickers).observe(document.documentElement, {childList: true, subtree: true});
  }
  const phrases = {
    en: {},
    lo: {
      'Home':'ໜ້າຫຼັກ','Market Events':'ງານຕະຫຼາດນັດ','Events':'ງານ','Categories':'ປະເພດ','News / Articles':'ຂ່າວສານ/ບົດຄວາມ','Contact':'ຕິດຕໍ່',
      'Login':'ເຂົ້າລະບົບ','Register':'ລົງທະບຽນ','Logout':'ອອກຈາກລະບົບ','Username':'ຊື່ຜູ້ໃຊ້','Username or email':'ຊື່ຜູ້ໃຊ້ ຫຼື ອີເມວ','Full name':'ຊື່ເຕັມ','Email':'ອີເມວ','Phone':'ເບີໂທລະສັບ','Phone number':'ເບີໂທລະສັບ','Password':'ລະຫັດຜ່ານ','Confirm password':'ຢືນຢັນລະຫັດຜ່ານ','Role':'ສິດຜູ້ໃຊ້','Normal User':'ຜູ້ໃຊ້ທົ່ວໄປ','Organizer':'ຜູ້ຈັດງານ','Organizers':'ຜູ້ຈັດງານ','Administrator':'ຜູ້ດູແລລະບົບ','Admin':'ຜູ້ດູແລລະບົບ',
      'Save':'ບັນທຶກ','Save user':'ບັນທຶກຜູ້ໃຊ້','Cancel':'ຍົກເລີກ','Edit':'ແກ້ໄຂ','Delete':'ລຶບ','Add User':'ເພີ່ມຜູ້ໃຊ້','Edit User':'ແກ້ໄຂຜູ້ໃຊ້','Manage Users':'ຈັດການຜູ້ໃຊ້','Manage Organizers':'ຈັດການຜູ້ຈັດງານ','Add Organizer':'ເພີ່ມຜູ້ຈັດງານ','Dashboard':'ພາບລວມລະບົບ','Overview':'ພາບລວມ','Approvals':'ລາຍການອະນຸມັດ','Organizer Requests':'ຄຳຮ້ອງຜູ້ຈັດງານ','Reviews':'ຣີວິວ','Reports':'ລາຍງານສະຖິຕິ',
      'Created':'ວັນທີສ້າງ','Actions':'ຈັດການ','Action':'ການກະທຳ','Status':'ສະຖານະ','Pending':'ລໍຖ້າອະນຸມັດ','Approved':'ອະນຸມັດແລ້ວ','Rejected':'ປະຕິເສດ','Approve':'ອະນຸມັດ','Reject':'ປະຕິເສດ','Search':'ຄົ້ນຫາ','Search...':'ຄົ້ນຫາ...','No user accounts found.':'ບໍ່ພົບບັນຊີຜູ້ໃຊ້','No organizers registered.':'ບໍ່ພົບຜູ້ຈັດງານ','Create account':'ສ້າງບັນຊີ','Sign in to your account':'ເຂົ້າສູ່ບັນຊີ','Already have an account?':'ມີບັນຊີແລ້ວບໍ?','Don\'t have an account?':'ຍັງບໍ່ມີບັນຊີບໍ?',
      'Save Organizer':'ບັນທຶກຜູ້ຈັດງານ','Organizer name':'ຊື່ຜູ້ຈັດງານ','Company name':'ຊື່ບໍລິສັດ','Contact email':'ອີເມວຕິດຕໍ່','Approval status':'ສະຖານະການອະນຸມັດ','User accounts':'ບັນຊີຜູ້ໃຊ້','Normal users':'ຜູ້ໃຊ້ທົ່ວໄປ','Administrators':'ຜູ້ດູແລລະບົບ','Total organizers':'ຜູ້ຈັດງານທັງໝົດ','Awaiting approval':'ລໍຖ້າອະນຸມັດ','Approved organizers':'ຜູ້ຈັດງານທີ່ອະນຸມັດແລ້ວ','All Events':'ງານທັງໝົດ','All Reviews':'ຣີວິວທັງໝົດ','Pending Events':'ງານລໍຖ້າອະນຸມັດ','Approved Events':'ງານທີ່ອະນຸມັດແລ້ວ','Event title':'ຊື່ງານ','Description':'ລາຍລະອຽດ','Location':'ສະຖານທີ່','Province':'ແຂວງ','Category':'ປະເພດ','Start date':'ວັນເລີ່ມຕົ້ນ','End date':'ວັນສິ້ນສຸດ','Time':'ເວລາ','Banner image':'ຮູບປົກ','Save Event':'ບັນທຶກງານ','Add Event':'ເພີ່ມງານ','Edit Event':'ແກ້ໄຂງານ','Back':'ກັບຄືນ','View':'ເບິ່ງ','Details':'ລາຍລະອຽດ','No events found.':'ບໍ່ພົບງານ','No reviews found.':'ບໍ່ພົບຣີວິວ','Current password':'ລະຫັດຜ່ານປັດຈຸບັນ','New password':'ລະຫັດຜ່ານໃໝ່','Confirm new password':'ຢືນຢັນລະຫັດຜ່ານໃໝ່','Profile':'ໂປຣໄຟລ໌','Update profile':'ອັບເດດໂປຣໄຟລ໌','Create your password':'ສ້າງລະຫັດຜ່ານ','Verify OTP':'ຢືນຢັນ OTP','Verify email':'ຢືນຢັນອີເມວ','Send OTP':'ສົ່ງ OTP','Confirm':'ຢືນຢັນ','Submit':'ສົ່ງ','Add':'ເພີ່ມ','Update':'ອັບເດດ','User saved successfully.':'ບັນທຶກຜູ້ໃຊ້ແລ້ວ','Organizer information saved.':'ບັນທຶກຂໍ້ມູນຜູ້ຈັດງານແລ້ວ','Delete this user account?':'ລຶບບັນຊີຜູ້ໃຊ້ນີ້ບໍ?'
    },
    zh: {
      'Home':'首页','Market Events':'集市活动','Events':'活动','Categories':'分类','News / Articles':'新闻/文章','Contact':'联系我们',
      'Login':'登录','Register':'注册','Logout':'退出登录','Username':'用户名','Username or email':'用户名或邮箱','Full name':'姓名','Email':'电子邮箱','Phone':'电话号码','Phone number':'电话号码','Password':'密码','Confirm password':'确认密码','Role':'角色','Normal User':'普通用户','Organizer':'活动组织者','Organizers':'活动组织者','Administrator':'管理员','Admin':'管理员',
      'Save':'保存','Save user':'保存用户','Cancel':'取消','Edit':'编辑','Delete':'删除','Add User':'添加用户','Edit User':'编辑用户','Manage Users':'用户管理','Manage Organizers':'组织者管理','Add Organizer':'添加组织者','Dashboard':'管理面板','Overview':'概览','Approvals':'审批','Organizer Requests':'组织者申请','Reviews':'评价','Reports':'统计报告',
      'Created':'创建时间','Actions':'操作','Action':'操作','Status':'状态','Pending':'待审批','Approved':'已批准','Rejected':'已拒绝','Approve':'批准','Reject':'拒绝','Search':'搜索','Search...':'搜索...','No user accounts found.':'未找到用户账户','No organizers registered.':'未找到组织者','Create account':'创建账户','Sign in to your account':'登录账户','Already have an account?':'已有账户？','Don\'t have an account?':'还没有账户？',
      'Save Organizer':'保存组织者','Organizer name':'组织者名称','Company name':'公司名称','Contact email':'联系邮箱','Approval status':'审批状态','User accounts':'用户账户','Normal users':'普通用户','Administrators':'管理员','Total organizers':'组织者总数','Awaiting approval':'待审批','Approved organizers':'已批准的组织者','All Events':'活动总数','All Reviews':'评价总数','Pending Events':'待审批活动','Approved Events':'已批准活动','Event title':'活动名称','Description':'描述','Location':'地点','Province':'省份','Category':'类别','Start date':'开始日期','End date':'结束日期','Time':'时间','Banner image':'活动图片','Save Event':'保存活动','Add Event':'添加活动','Edit Event':'编辑活动','Back':'返回','View':'查看','Details':'详情','No events found.':'未找到活动','No reviews found.':'未找到评价','Current password':'当前密码','New password':'新密码','Confirm new password':'确认新密码','Profile':'个人资料','Update profile':'更新资料','Create your password':'创建密码','Verify OTP':'验证验证码','Verify email':'验证邮箱','Send OTP':'发送验证码','Confirm':'确认','Submit':'提交','Add':'添加','Update':'更新','User saved successfully.':'用户已保存','Organizer information saved.':'组织者信息已保存','Delete this user account?':'确定删除此用户账户吗？'
    }
  };

  const keyTranslations = {
    search: {lo:'ຄົ້ນຫາ...',zh:'搜索...',en:'Search...'},
    home: {lo:'ໜ້າຫຼັກ',zh:'首页',en:'Home'},
    events: {lo:'ງານຕະຫຼາດນັດ',zh:'集市活动',en:'Market Events'},
    locations: {lo:'ສະຖານທີ່',zh:'地点',en:'Locations'},
    categories: {lo:'ປະເພດງານ',zh:'活动分类',en:'Categories'},
    news: {lo:'ຂ່າວສານ/ບົດຄວາມ',zh:'新闻/文章',en:'News / Articles'},
    contact: {lo:'ຕິດຕໍ່',zh:'联系我们',en:'Contact'},
    footerDesc: {lo:'ເວັບໄຊລວບລວມງານຕະຫຼາດນັດໃນປະເທດລາວ ຊ່ວຍໃຫ້ທ່ານຄົ້ນຫາກິດຈະກຳໄດ້ງ່າຍຂຶ້ນ',zh:'老挝集市活动平台，帮助您轻松发现精彩活动。',en:'A Laos market-event platform that makes it easy to discover local activities.'},
    quickMenu: {lo:'ເມນູດ່ວນ',zh:'快捷菜单',en:'Quick menu'},
    support: {lo:'ສະໜັບສະໜູນ',zh:'帮助与支持',en:'Support'},
    faq: {lo:'ຄຳຖາມທີ່ພົບເລື້ອຍ',zh:'常见问题',en:'FAQ'},
    guide: {lo:'ຄູ່ມືການໃຊ້ງານ',zh:'使用指南',en:'User guide'},
    privacy: {lo:'ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ',zh:'隐私政策',en:'Privacy policy'}
  };

  Object.assign(keyTranslations, {
    adminOverview: {lo:'ພາບລວມລະບົບ',zh:'系统概览',en:'System overview'},
    adminApprovals: {lo:'ອະນຸມັດງານ',zh:'活动审批',en:'Event approvals'},
    adminUsers: {lo:'ຈັດການຜູ້ໃຊ້',zh:'用户管理',en:'Manage users'},
    adminOrganizers: {lo:'ຜູ້ຈັດງານ',zh:'组织者',en:'Organizers'},
    adminOrganizerRequests: {lo:'ຄຳຮ້ອງຜູ້ຈັດງານ',zh:'组织者申请',en:'Organizer requests'},
    adminReviews: {lo:'ຈັດການຣີວິວ',zh:'评价管理',en:'Manage reviews'},
    adminReports: {lo:'ລາຍງານສະຖິຕິ',zh:'统计报告',en:'Reports'},
    organizerDashboard: {lo:'ແຜງຄວບຄຸມ',zh:'控制面板',en:'Dashboard'},
    organizerEvents: {lo:'ຈັດການງານຕະຫຼາດນັດ',zh:'活动管理',en:'Manage events'},
    organizerAddEvent: {lo:'ເພີ່ມງານໃໝ່',zh:'添加活动',en:'Add event'},
    organizerStalls: {lo:'ຈັດການຮ້ານຄ້າ',zh:'摊位管理',en:'Manage stalls'},
    organizerReports: {lo:'ລາຍງານ ແລະ ຣີວິວ',zh:'报告与评价',en:'Reports and reviews'},
    organizerProfile: {lo:'ຕັ້ງຄ່າໂປຣໄຟລ໌',zh:'个人资料设置',en:'Profile settings'},
    online: {lo:'ລະບົບປົກກະຕິ',zh:'系统运行正常',en:'System online'},
    logout: {lo:'ອອກຈາກລະບົບ',zh:'退出登录',en:'Log out'},
    adminPanel: {lo:'ແຜງຄວບຄຸມ Admin',zh:'管理员面板',en:'Admin Panel'},
    organizerRole: {lo:'ຜູ້ຈັດງານ',zh:'活动组织者',en:'Organizer'}
    ,language: {lo:'ພາສາ',zh:'语言',en:'Language'}
    ,privacyTitle: {lo:'ນະໂຍບາຍຄວາມເປັນສ່ວນຕົວ',zh:'隐私政策',en:'Privacy Policy'}
    ,helpTitle: {lo:'ຄູ່ມືການໃຊ້ງານ',zh:'使用指南',en:'User Guide'}
    ,blogTitle: {lo:'ຂ່າວສານ ແລະ ບົດຄວາມ LAOeventMarket',zh:'LAOeventMarket 新闻与文章',en:'LAOeventMarket News & Articles'}
    ,eventBack: {lo:'ກັບໜ້າຫຼັກ',zh:'返回首页',en:'Back to home'}
    ,eventLocation: {lo:'ສະຖານທີ່ຈັດງານ',zh:'活动地点',en:'Event location'}
    ,eventDates: {lo:'ໄລຍະເວລາຈັດງານ',zh:'活动日期',en:'Event dates'}
    ,eventTo: {lo:'ຫາ',zh:'至',en:'to'}
    ,eventHours: {lo:'ເວລາເປີດ-ປິດ',zh:'开放时间',en:'Opening hours'}
    ,eventDescription: {lo:'ລາຍລະອຽດງານ',zh:'活动详情',en:'Event details'}
    ,eventOrganizer: {lo:'ຜູ້ຈັດງານອົງກອນ / ບຸກຄົນ',zh:'组织/个人主办方',en:'Organization / individual organizer'}
    ,eventStalls: {lo:'ຮ້ານຄ້າພາຍໃນງານ',zh:'活动摊位',en:'Event stalls'}
    ,stallUnit: {lo:'ຮ້ານ',zh:'个摊位',en:'stalls'}
    ,stallNumber: {lo:'ລັອກ',zh:'摊位号',en:'Booth'}
    ,eventNoStalls: {lo:'ຍັງບໍ່ມີຂໍ້ມູນຮ້ານຄ້າໃນງານນີ້',zh:'暂无活动摊位信息',en:'No stall information for this event yet'}
    ,eventReviews: {lo:'ຣີວິວ ແລະ ຄະແນນ',zh:'评价与评分',en:'Reviews and ratings'}
    ,totalLabel: {lo:'ທັງໝົດ',zh:'共',en:'Total'}
    ,reviewUnit: {lo:'ຣີວິວ',zh:'条评价',en:'reviews'}
    ,writeReview: {lo:'ຂຽນຣີວິວຂອງທ່ານ:',zh:'撰写您的评价：',en:'Write your review:'}
    ,reviewPlaceholder: {lo:'ຂຽນຄຳຄິດເຫັນ ຫຼື ຄວາມປະທັບໃຈຂອງທ່ານຕໍ່ງານນີ້...',zh:'写下您对本活动的意见或感受...',en:'Share your thoughts or impressions of this event...'}
    ,submitReview: {lo:'ສົ່ງຣີວິວ',zh:'提交评价',en:'Submit review'}
    ,please: {lo:'ກະລຸນາ',zh:'请',en:'Please'}
    ,loginToReview: {lo:'ເຂົ້າສູ່ລະບົບ',zh:'登录',en:'log in'}
    ,reviewLoginPrompt: {lo:'ເພື່ອໃຫ້ຄະແນນ ແລະ ຂຽນຣີວິວ',zh:'以便评分和撰写评价',en:'to rate and write a review'}
    ,eventNoReviews: {lo:'ຍັງບໍ່ມີຣີວິວສຳລັບງານນີ້',zh:'此活动暂无评价',en:'No reviews for this event yet'}
    ,verifiedAccount: {lo:'ບັນຊີນີ້ໄດ້ຮັບການຢືນຢັນແລ້ວ',zh:'此账号已通过认证',en:'This account is verified'}
  });

  const isAdminPage = adminOnlyLao;
  let language = isAdminPage ? 'lo' : (localStorage.getItem('lem_lang') || localStorage.getItem('preferred_lang') || 'lo');

  function translate() {
    document.documentElement.lang = language;
    const page = location.pathname.split('/').pop();
    if (page === 'event.php') translateEventPage();
    const titleKey = { 'privacy.php':'privacyTitle', 'help.php':'helpTitle', 'blog.php':'blogTitle' }[page];
    if (titleKey && keyTranslations[titleKey]?.[language]) {
      document.title = `${keyTranslations[titleKey][language]} - LAOeventMarket`;
      const pageHeading = document.querySelector('body > section h1');
      if (pageHeading) pageHeading.textContent = keyTranslations[titleKey][language];
    }
    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.dataset.i18n;
      const translation = keyTranslations[key]?.[language];
      if (!translation) return;
      const textNode = [...el.childNodes].reverse().find((child) => child.nodeType === Node.TEXT_NODE && child.nodeValue.trim());
      if (textNode) textNode.nodeValue = textNode.nodeValue.replace(textNode.nodeValue.trim(), translation);
      else el.textContent = translation;
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
      const translation = keyTranslations[el.dataset.i18nPlaceholder]?.[language];
      if (translation) el.setAttribute('placeholder', translation);
    });

    const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
    const nodes = [];
    while (walker.nextNode()) nodes.push(walker.currentNode);
    nodes.forEach((node) => {
      if (!node.parentElement || node.parentElement.closest('script,style,textarea,[contenteditable="true"],[data-no-translate]')) return;
      if (!node.hasOwnProperty('__translationSource')) {
        Object.defineProperty(node, '__translationSource', {value: node.nodeValue.trim(), writable: true});
      }
      const source = node.__translationSource;
      const replacement = phrases[language]?.[source];
      if (replacement && source) {
        const leading = node.nodeValue.match(/^\s*/)?.[0] || '';
        const trailing = node.nodeValue.match(/\s*$/)?.[0] || '';
        node.nodeValue = leading + replacement + trailing;
      } else if (language === 'en' && phrases.lo[source]) {
        node.nodeValue = (node.nodeValue.match(/^\s*/)?.[0] || '') + source + (node.nodeValue.match(/\s*$/)?.[0] || '');
      }
    });

    document.querySelectorAll('[placeholder],[title],[aria-label]').forEach((el) => {
      ['placeholder','title','aria-label'].forEach((attr) => {
        const sourceAttr = `data-i18n-source-${attr}`;
        if (!el.hasAttribute(sourceAttr)) el.setAttribute(sourceAttr, el.getAttribute(attr) || '');
        const original = el.getAttribute(sourceAttr);
        if (phrases[language]?.[original]) el.setAttribute(attr, phrases[language][original]);
        else if (language === 'en') el.setAttribute(attr, original);
      });
    });

    document.querySelectorAll('[data-i18n-title]').forEach((el) => {
      const translation = keyTranslations[el.dataset.i18nTitle]?.[language];
      if (translation) el.setAttribute('title', translation);
    });
    document.querySelectorAll('#langText').forEach((el) => {
      el.textContent = {lo:'ລາວ',zh:'中文',en:'English'}[language] || 'ລາວ';
    });
    const pickerLabel = document.getElementById('pickerLanguageLabel');
    if (pickerLabel) pickerLabel.textContent = keyTranslations.language[language];
    if (!isAdminPage) {
      localStorage.setItem('lem_lang', language);
      localStorage.setItem('preferred_lang', language);
    }
  }

  function translateEventPage() {
    const setText = (element, key) => {
      const value = keyTranslations[key]?.[language];
      if (!element || !value) return;
      const textNode = [...element.childNodes].reverse().find((node) => node.nodeType === Node.TEXT_NODE && node.nodeValue.trim());
      if (textNode) textNode.nodeValue = textNode.nodeValue.replace(textNode.nodeValue.trim(), value);
      else element.append(document.createTextNode(value));
    };
    setText(document.querySelector('.btn-back'), 'eventBack');
    const infoLabels = document.querySelectorAll('.info-grid .info-text small');
    setText(infoLabels[0], 'eventLocation');
    setText(infoLabels[1], 'eventDates');
    setText(infoLabels[2], 'eventHours');
    const dateRange = document.querySelectorAll('.info-grid .info-text span')[1];
    if (dateRange) [...dateRange.childNodes].filter((node) => node.nodeType === Node.TEXT_NODE).forEach((node) => {
      node.nodeValue = node.nodeValue.replace(/\s+[\u0E80-\u0EFF]+\s+/, ` ${keyTranslations.eventTo[language]} `);
    });
    setText(document.querySelector('.section-desc h3'), 'eventDescription');
    setText(document.querySelector('.org-details > p'), 'eventOrganizer');
    const stallsTitle = document.querySelector('.stalls-header h2');
    if (stallsTitle) {
      const count = stallsTitle.textContent.match(/\((\d+)\s*[^)]*\)/)?.[1] ?? '0';
      const icon = stallsTitle.querySelector('i');
      stallsTitle.replaceChildren(...(icon ? [icon] : []), document.createTextNode(` ${keyTranslations.eventStalls[language]} (${count} ${keyTranslations.stallUnit[language]})`));
    }
    setText(document.querySelector('.empty-stalls p'), 'eventNoStalls');
    const reviewTitle = document.querySelector('.reviews-header h2');
    if (reviewTitle) setText(reviewTitle, 'eventReviews');
    const reviewCount = document.querySelector('.rating-summary small');
    if (reviewCount) {
      const count = reviewCount.textContent.match(/\d+/)?.[0] ?? '0';
      reviewCount.textContent = `(${keyTranslations.totalLabel[language]} ${count} ${keyTranslations.reviewUnit[language]})`;
    }
    setText(document.querySelector('.review-form h4'), 'writeReview');
    const reviewBox = document.querySelector('.review-form textarea');
    if (reviewBox) reviewBox.setAttribute('placeholder', keyTranslations.reviewPlaceholder[language]);
    const submit = document.querySelector('.btn-submit-review');
    setText(submit, 'submitReview');
    const prompt = document.querySelector('.reviews-section > div[style*="dashed"]');
    if (prompt) {
      const link = prompt.querySelector('a');
      const textNodes = [...prompt.childNodes].filter((node) => node.nodeType === Node.TEXT_NODE && node.nodeValue.trim());
      if (textNodes.length >= 2 && link) {
        textNodes[0].nodeValue = ` ${keyTranslations.please[language]} `;
        setText(link, 'loginToReview');
        textNodes[1].nodeValue = ` ${keyTranslations.reviewLoginPrompt[language]} `;
      }
    }
    setText(document.querySelector('.reviews-list > p'), 'eventNoReviews');
    document.querySelectorAll('.verified-badge').forEach((badge) => badge.setAttribute('title', keyTranslations.verifiedAccount[language]));
  }

  function setLanguage(next) {
    if (isAdminPage) next = 'lo';
    if (!['lo','zh','en'].includes(next)) return;
    language = next;
    translate();
  }

  function addLanguagePicker() {
    if (isAdminPage) {
      document.querySelectorAll('.site-language-picker').forEach((picker) => picker.remove());
      document.querySelectorAll('.lang-wrap, #langBtn, #langMenu').forEach((picker) => picker.remove());
      return;
    }
    if (document.getElementById('langBtn') || document.querySelector('.site-language-picker')) return;
    const wrapper = document.createElement('label');
    wrapper.className = 'site-language-picker';
    wrapper.setAttribute('data-no-translate', '');
    wrapper.innerHTML = '<span id="pickerLanguageLabel">Language</span><select aria-label="Language"><option value="lo">ລາວ</option><option value="zh">中文</option><option value="en">English</option></select>';
    wrapper.style.cssText = 'position:fixed;right:14px;bottom:14px;z-index:9999;display:flex;align-items:center;gap:7px;padding:7px 10px;background:#fff;border:1px solid #e2e8f0;border-radius:9px;box-shadow:0 4px 14px #0f172a20;font:12px sans-serif;color:#334155';
    wrapper.querySelector('select').style.cssText = 'border:0;background:transparent;color:#111827;font:12px sans-serif;outline:none;cursor:pointer';
    wrapper.querySelector('select').value = language;
    wrapper.querySelector('select').addEventListener('change', (event) => setLanguage(event.target.value));
    document.body.appendChild(wrapper);
  }

  document.addEventListener('click', (event) => {
    const option = event.target.closest('[data-lang]');
    if (option) {
      event.preventDefault();
      setLanguage(option.dataset.lang);
    }
    const button = event.target.closest('#langBtn');
    if (button) document.getElementById('langMenu')?.classList.toggle('show');
    else if (!event.target.closest('.lang-wrap')) document.getElementById('langMenu')?.classList.remove('show');

    const heart = event.target.closest('.heart');
    if (heart) {
      event.preventDefault();
      event.stopPropagation();
      heart.classList.toggle('active');
      const icon = heart.querySelector('i');
      if (icon) icon.className = heart.classList.contains('active') ? 'ti ti-heart-filled' : 'ti ti-heart';
    }
  });

  document.addEventListener('DOMContentLoaded', () => { addLanguagePicker(); translate(); });
  if (document.readyState !== 'loading') { addLanguagePicker(); translate(); }
})();
