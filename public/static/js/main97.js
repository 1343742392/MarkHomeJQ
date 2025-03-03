import{request}from"./request.js";import{setCookie,getCookie,clearCookie}from"./cookie-tool.js";import{binaryToUtf8}from"./utf8-1.js";let url="",markBooks={forEachFile:function(e){this.forEachFolder(t=>{let l=0;for(let i in t.files)if(l=e(t.files[i]))return 1})},forEachFolder:function(e,t=markBooks){let l=0,i=Object.keys(markBooks);for(let n=0;n<i.length;n++){if(l=e(markBooks[i[n]]))return 1;if(t.folders)return this.forEachFolder(e,t.folders)}},getFolder:function(e){if(void 0===markBooks||!Array.isArray(e))return null;if(0==e.length)return markBooks;let t=markBooks;if(!("folders"in t))return null;for(let l=0;;l++){if(!(e[l]in t.folders))return null;if(l>=e.length-1&&e[l]in t.folders)return t.folders;t=t.folders[e[l]]}return t},setFolder:function(e,t){if(void 0===markBooks)throw new Error("markBooks is not defined");let l=markBooks;for(let i=0;;i++){if(e[i]in l||(l[e[i]]={}),i+1==e.length)return void Object.assign(l,t);l=l[e[i]]}},deleteAll:function(){let e=Object.keys(markBooks);for(let t=0;t<e.length;t++)delete markBooks[e[t]]},createPath:function(e,t,l){if(void 0===markBooks)throw new Error("markBooks is not defined");let i=markBooks;for(let n=0;;n++){if("folders"in i||(i.folders={}),!(e[n]in i.folders)){let o=markBooks.newFolder(e[n]);null!=t&&0==n&&(o[e[n]].folderDiv=t),null!=l&&(o[e[n]].itemDiv=l[n]),"folders"in i||(i.folders={}),i.folders[e[n]]=o[e[n]]}if(n+1==e.length){let t={};return t[e[n]]=i.folders[e[n]],t}i=i.folders[e[n]]}return i},newFolder:function(e){let t={};return t[e]={},t[e].files=[],t[e].folders={},t[e].folderDiv=null,t},getAllFiles:function(e){if(void 0===markBooks||!Array.isArray(e))return null;let t=e[e.length-1],l=this.getFolder(e);if(null==l)return[];if(null==(l=t in l?l[t]:markBooks))return[];let i=[];if("files"in l)for(let e in l.files)i.push(l.files[e]);for(let t in l.folders)i=i.concat(this.getAllFiles(e.concat(t)));return i}};const properties=[{key:"forEachFile",writable:!1,enumerable:!1},{key:"forEachFolder",writable:!1,enumerable:!1},{key:"deleteAll",writable:!1,enumerable:!1},{key:"createPath",writable:!1,enumerable:!1},{key:"newFolder",writable:!1,enumerable:!1},{key:"setFolder",writable:!1,enumerable:!1},{key:"getFolder",writable:!1,enumerable:!1},{key:"getAllFiles",writable:!1,enumerable:!1}];properties.forEach(e=>{Object.defineProperty(markBooks,e.key,{writable:e.writable,enumerable:e.enumerable})});let mbLeft=null,foldersDiv=null,backfoloderDiv=null,activePath=[],homeState="ide",searchEngine="baidu",searchBtn=null,searchWordInput=null,engineUrls={kuake:"https://quark.sm.cn/s?q=",bing:"https://cn.bing.com/search?q=",google:"https://www.google.com/search?q=",baidu:"https://www.baidu.com/s?ie=UTF-8&wd="},engineBtns={},folderHtml='\n<div class="folder row w-100 click-pointer " \'>\n    <p class="folder-name">{{name}}</p>\n    <svg  class="folder-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">\n        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>\n    </svg>\n</div>\n',contentDiv=null,fileHtml='\n<div  class="\nfile-row \nclick-pointer \nflex-md-row \nalign-items-center "  title=\'{{url}}\' >\n    <div class="item-left">\n        <img class=\'file-ico\' src=\'{{icon}}\' onerror="src=\'/static/icons/defIcon.png\'"\n        >\n    </div>\n\n    <div class="item-right">\n        <p class="file-name one-line">{{name}}</p>\n    </div>\n\n    <div class="check-div" style="display:none">\n        <input class="checkbox" type="checkbox" >\n    </div>\n</div>\n',contentFolderHtml='\n<div  class="\nfile-row \nclick-pointer \nflex-md-row \nalign-items-center ">\n    <div class="item-left">\n        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">\n            <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139q.323-.119.684-.12h5.396z"/>\n        </svg>\n    </div>\n\n    <div class="item-right">\n        <p class="file-name one-line">{{name}}</p>\n    </div>\n\n    <div class="check-div" style="display:none">\n        <input class="checkbox" type="checkbox" >\n    </div>\n</div>\n',backFoloderHtml='\n<div  class="\nfile-row \nclick-pointer \nflex-md-row \nalign-items-center " tag=\'back\'>\n    <div class="item-left">\n        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">\n        <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/> \n        </svg>\n    </div>\n\n    <div class="item-right">\n        <p class="file-name one-line">返回</p>\n    </div>\n</div>\n',mBsHtml='\n<!DOCTYPE NETSCAPE-Bookmark-file-1>\n<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">\n<TITLE>Bookmarks</TITLE>\n<H1>Bookmarks</H1>\n<DL><p>\n    {{folders}}\n</DL><p>\n',mBsFolderHtml='\n<DT><H3 ADD_DATE="1640677859" LAST_MODIFIED="1675407235" PERSONAL_TOOLBAR_FOLDER="true">{{folderName}}</H3>\n    <DL><p>\n',mBsfileHtml='\n<DT><A HREF="{{url}}" ADD_DATE="1664288136" > {{name}}</A>\n',loginPage=null,mailInput=null,verifBtn=null,verifCodeInput=null,loginOrRegBtn=null,homePage=null,userPage=null,userMailP=null,userMail=null,userToken=null,addUrlInput=null,addNameInput=null,pathInput=null,folderSelectDiv=null,folderSelectHtml='\n<a class="dropdown-item" >{{folderName}}</a>\n',addFileBtn=null,editFolderPage=null,editFilePage=null,editFileData=null,editFolderPath="",checkFiles=[],checkFolders=[],copyBtn=null,copyPath=[],pasteBtn=null,copyfiles=[],dialogDiv=null,dailogTitleH=null,dialogMsgP=null,dialogCloseBtn=null,dialogCloseAb=!0,pageList=[],responseInfo=null;function saveResInfo(e){responseInfo=e.currentTarget.response}function showInfo(e,t,l=!0){dialogDiv.fadeIn(),dailogTitleH.text(e),dialogMsgP.text(t),dialogCloseAb=l,l||dialogCloseBtn.hide()}function hideInfo(e=!1){dialogCloseAb|e&&dialogDiv.fadeOut()}function switchPage(e){e!=editFilePage&&e!=editFolderPage||(homeState="editing"),pageList[pageList.length-1].hide(),e.show(),pageList.push(e)}function back(){if("del"==homeState||"edit"==homeState){$("#homeHeadDiv").show(),$("#searchDiv").show(),$("#homeBackBtn").hide();let t=$("#editBtns");var e=t.attr("style")||"";""==e?t.attr("style","display:none !important;"):(e=e.replace(/display:\s*[^;]+;/gi,"display: block !important;"),t.attr("style",e)),setAllCheck(!0),homeState="ide"}"editing"==homeState&&($("#homeBackBtn").show(),homeState="edit"),pageList.length>1&&(pageList[pageList.length-1].hide(),pageList.pop(),pageList[pageList.length-1].show(),pageList[pageList.length-1]==homePage&&setFolderMargin())}function sendVerif(){verifBtn.attr("disabled",!0),setTimeout(()=>{verifBtn.attr("disabled",!1)},3e4),/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(mailInput.val())?request("mail="+encodeURIComponent(mailInput.val()),url+"/index.php/index/index/sendMail",function(e){saveResInfo(e);let t=JSON.parse(e.currentTarget.response);401!=t.code?200==t.code&&showInfo("发送验证码成功","验证码已发送到邮箱，请注意查收"):showInfo("发送验证码失败",t.data)},"post"):showInfo("输入错误","邮箱格式不对或者为空")}function selectEngine(e){let t=e.currentTarget.id;engineBtns[searchEngine].removeAttr("disabled"),engineBtns[searchEngine].click(selectEngine),engineBtns[t].prop("disabled","true"),engineBtns[t].click(null),searchEngine=t,setCookie("engine",t,999)}function regOrReg(){let e=mailInput.val(),t=verifCodeInput.val();e&&t?/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(e)?request(`mail=${e}&code=${t}`,url+"/index.php/index/index/LoginOrRegister",function(t){saveResInfo(t);let l=JSON.parse(t.currentTarget.response);200==l.code?(setCookie("mail",e,100),userMailP.text(e),setCookie("token",l.token,100),userToken=l.token,userMail=e,switchPage(homePage),"data"in l&&(importFiles(l.data),saveMarkBook(),clickFirst())):401==l.code&&showInfo("验证失败",l.data)},"post"):showInfo("输入错误","邮箱格式不对"):showInfo("输入错误","邮箱或者验证码没填")}function findE(){loginPage=$("#loginPage"),mailInput=$("#mailInput"),verifBtn=$("#verifBtn"),verifCodeInput=$("#verifCodeInput"),loginOrRegBtn=$("#loginOrRegBtn"),homePage=$("#homePage"),userPage=$("#userPage"),editFolderPage=$("#editFolderPage"),editFilePage=$("#editFilePage"),copyBtn=$("#copyBtn"),pasteBtn=$("#pasteBtn"),userMailP=$("#userMailP"),dialogDiv=$("#dialogDiv"),dailogTitleH=$("#dailogTitleH"),dialogMsgP=$("#dialogMsgP"),dialogCloseBtn=$("#dialogCloseBtn"),mbLeft=$("#mb-left"),foldersDiv=$("#foldersDiv"),contentDiv=$("#contentDiv"),addUrlInput=$("#addUrlInput"),addNameInput=$("#addNameInput"),pathInput=$("#folderNameInput"),folderSelectDiv=$("#folderSelectDiv"),addFileBtn=$("#addFileBtn"),searchBtn=$("#searchBtn"),searchWordInput=$("#searchWordInput")}function search(){window.open(engineUrls[searchEngine]+searchWordInput.val(),"newwindow"+searchWordInput.val())}function setEvent(){verifBtn.click(sendVerif),dialogCloseBtn.click(hideInfo),dialogDiv.click(hideInfo),loginOrRegBtn.click(regOrReg),window.back=back,window.delFile=delFiles,$("#dropdown").click(dropdown),addFileBtn.click(function(){addFile()}),$("#setSvg").click(function(e){switchPage($("#setPage"))}),searchBtn.click(()=>{search()}),searchWordInput.keydown(function(e){13==e.which&&search()}),$("#userSvg").click(function(){switchPage(userPage)}),$("#loginOutBtn").click(function(){clearCookie("mail"),clearCookie("token"),window.localStorage.removeItem("mark_books"),location.reload()}),$("#addBtn").click(function(){setSelect(),switchPage($("#addPage"))}),$("#editBtn").click(function(){homeState="edit",switchPage(homePage),$("#homeHeadDiv").hide(),$("#searchDiv").hide(),$("#homeBackBtn").show(),$("#editBtns").show(),setAllCheck(!1)}),copyBtn.click(copyClick),pasteBtn.click(pasteClick),$("#deleteBtn").click(deleteClick),$("#editObjectBtn").click(editObjectClick),$("#selectAll").click(selectAllClick),editFolderPage.find("button").first().click(function(){editFolder(editFolderPath,editFolderPage.find("input").prop("value"))}),editFilePage.find("button").first().click(function(){editFile()}),$("#favoritesInput").change(e=>{importHtmlMB(e)}),$("#outputBtn").click(()=>{outputHtmlMB()}),$("#engineBtn").click(function(){switchPage($("#enginePage")),Object.keys(engineUrls).forEach(e=>{engineBtns[e]=$("#"+e),e!=searchEngine?engineBtns[e].click(selectEngine):engineBtns[e].prop("disabled"," true")})})}function saveMarkBook(){let e=markBooks.getAllFiles([]),t=JSON.stringify(e,function(e,t){return"folderDiv"==e|"fileDiv"==e|"itemDiv"==e|"ico"==e|"create_time"==e|"user"==e?void 0:t});window.localStorage.mark_books=encodeURIComponent(t)}function checkClick(e){$(this).prop("checked")?"folder"==e.data.type?checkFolders.push(e.data.data):checkFiles.push(e.data.data):"folder"==e.data.type?checkFolders.splice(checkFolders.indexOf(e.data.data),1):checkFiles.splice(checkFiles.indexOf(e.data.data),1),e.stopPropagation()}function setAllCheck(e=!1,t=!1){let l=markBooks.getFolder(activePath);if(!l)return;let i=activePath[activePath.length-1];l[i]&&(Object.keys(l[i].folders).forEach(n=>{let o=l[i].folders[n].itemDiv;if(!o)return;let r=o.children().last();e?r.hide():r.show(),r.children().first().prop("checked",t)}),l[i].files.forEach(l=>{let i=l.fileDiv;if(!i)return;let n=i.children().last();e?n.hide():n.show(),n.children().first().prop("checked",t)}),checkFiles=[],checkFolders=[])}function copyClick(e){if(0==checkFiles.length&&0==checkFolders.length)return void showInfo("复制","请先勾选文件或文件夹");pasteBtn.toggle(),copyBtn.toggle(),copyPath=activePath.slice();let t=[];t=JSON.parse(JSON.stringify(t.concat(checkFiles),function(e,t){return"fileDiv"==e|"itemDiv"==e?void 0:t}));for(let e in t)t[e].folder=[];for(let e in checkFolders){let l=checkFolders[e],i=JSON.parse(l),n=JSON.parse(JSON.stringify(markBooks.getAllFiles(i),function(e,t){return"fileDiv"==e|"itemDiv"==e?void 0:t}));for(let e in n){let t=JSON.parse(n[e].folder);n[e].folder=t.slice(copyPath.length)}t=t.concat(n)}copyfiles=t}function pasteClick(e){if(pasteBtn.toggle(),copyBtn.toggle(),0!=copyfiles.length){for(let e in copyfiles){let t=copyfiles[e];t.folder=activePath.concat(t.folder)}addFilesNet(copyfiles,function(e,t){e?(showInfo("粘贴成功","粘贴成功"),importFiles(t),saveMarkBook()):showInfo("粘贴失败","粘贴失败")}),setAllCheck(!1),copyfiles=[],copyPath=[]}}function deleteClick(e){if(0!=checkFiles.length||0!=checkFolders.length){for(let e in checkFolders){let t=JSON.parse(checkFolders[e]),l=markBooks.getAllFiles(t);checkFiles=checkFiles.concat(l)}showInfo("删除中","删除中，请稍候"),delFiles(checkFiles,function(e){if(e){for(let e in checkFolders){let t=JSON.parse(checkFolders[e]),l=markBooks.getFolder(t),i=t[t.length-1],n=l[i].itemDiv,o=l[i].folderDiv;o&&o.remove(),n.remove()}var t=markBooks.getFolder(activePath);let e=activePath[activePath.length-1],l=t[e].files,i=t[e].folders;if(0==l.length&&0==Object.keys(i).length){let l=t[e].folderDiv;l&&l.remove(),delete t[e],activePath.pop(),clickFirst()}checkFiles=[],checkFolders=[],setAllCheck(!1)}else showInfo("删除失败","可能是选择文件或文件夹内文件过多，请分批删除")})}else showInfo("删除","请先勾选文件或文件夹")}function editObjectClick(e){if(checkFiles.length+checkFolders.length>1)showInfo("编辑","请选择单个文件或文件夹");else{if(1==checkFiles.length){let e=checkFiles[0];editFileData=e,switchPage(editFilePage),$("#fileNameInput").val(e.name),$("#fileUrlInput").val(e.url)}else if(1==checkFolders.length){let e=checkFolders[0],t=JSON.parse(e);switchPage(editFolderPage),editFolderPath=t,$("#folderName ").val(t[t.length-1])}setAllCheck(!1,!1)}}function selectAllClick(e){let t=[],l=0,i=markBooks.getFolder(activePath);if(!i)return;let n=activePath[activePath.length-1];if(!n)return;let o=i[n].files;for(let e in o){let i=o[e].fileDiv.find("input");i.prop("checked")&&l++,t.push(i)}let r=i[n].folders;for(let e in r){let i=r[e].itemDiv.find("input");i.prop("checked")&&l++,t.push(i)}for(let e in t)l!=t.length&&t[e].prop("checked")||t[e].click()}function editFile(){let e=$("#fileNameInput").prop("value"),t=$("#fileUrlInput").prop("value");if(!t||!e)return void showInfo("填写错误","网址 名字 收藏文件 不能为空");if(e.length>100&&(e=e.substring(0,100)),t.length>2e3)return void showInfo("填写错误","url过长");if(0!=t.indexOf("http"))return void showInfo("填写错误","网址要填写完整包括http(s)://...");let l=encodeURIComponent(e),i=encodeURIComponent(t);request(`mail=${userMail}&token=${userToken}&id=${editFileData.i}&name=${l}&url=${i}`,url+"/index.php/index/index/editFile",l=>{saveResInfo(l);let i=JSON.parse(l.currentTarget.response);if("code"in i&"200"==i.code){showInfo("修改成功","修改成功");let l=editFileData;l.name=e,l.url=t;let i=l.fileDiv;i.attr("title",l.url),i.find(".file-name.one-line").text(l.name),saveMarkBook()}else showInfo("修改失败","修改失败")},"post")}function editFolder(e,t){if(!(t=t.trim()))return void showInfo("填写错误","文件名字不能为空");if(t.length>50)return void showInfo("填写错误","文件名字太长了");let l=encodeURIComponent(JSON.stringify(e));e.pop(),e.push(t);let i=encodeURIComponent(JSON.stringify(e));request(`mail=${userMail}&token=${userToken}&fromPath=${l}&toPath=${i}`,url+"/index.php/index/index/editFolder",e=>{saveResInfo(e);let l=JSON.parse(e.currentTarget.response);"code"in l&"200"==l.code?(sync(()=>{editFolderPath=t}),showInfo("修改成功","修改成功")):showInfo("修改失败","修改失败")},"post")}function delFiles(e,t=null){let l=[];for(let t in e)l.push(e[t].i);let i=encodeURIComponent(JSON.stringify(l));return request(`token=${userToken}&is=${i}`,url+"/index.php/index/index/delMarkBooks",function(l){saveResInfo(l);let i=JSON.parse(l.currentTarget.response);if("code"in i&200==i.code){for(let t in e){let l=e[t],i=l.fileDiv;i&&i.remove();let n=JSON.parse(l.folder),o=markBooks.getFolder(n)[n[n.length-1]].files;o.splice(o.indexOf(l),1)}return t&&t(!0),saveMarkBook(),void showInfo("删除成功","删除书签成功")}t&&t(!1)},"post"),!1}function addFolderView(e,t){let l=contentFolderHtml.replace(/\{\{name\}\}/g,t),i=$(l),n=markBooks.getFolder(e);if(null!==n&&e.length>0){let t=n[e[e.length-1]].files;if(t.length>0){let e=t[0].fileDiv;if(e)return e.before(i),i}}return contentDiv.append(i),i}function addFolderLocal(e=["其他"]){let t=null,l=[],i=[],n=null;for(let o=0;o<e.length;o++){t=e[o];let r=null,a=JSON.parse(JSON.stringify(i));if(i.push(t),null==markBooks.getFolder(i)){0==o&&(foldersDiv.append(folderHtml.replace("{{name}}",t)),(n=foldersDiv.children().last()).on("click",{path:JSON.stringify(i)},folderClick)),(r=addFolderView(a,t)).on("click",{path:JSON.stringify(i)},folderClick);let e=r.children().last();"edit"==homeState&&e.show(),r.find("input").on("click",{data:JSON.stringify(i),type:"folder"},checkClick);let l=JSON.stringify(activePath);"[]"==l|JSON.stringify(a)!=l&&r.hide()}l.push(r)}let o=markBooks.createPath(e,n,l);return setFolderMargin(),o}function toggleFolder(e){let t=null,l=e[e.length-1],i=markBooks.getFolder(e),n=i[l].files;if(n)for(t in n){let e=n[t];e.fileDiv?(e.fileDiv.remove(),e.fileDiv=null):addFileView(e)}backfoloderDiv&&(backfoloderDiv.remove(),backfoloderDiv=null),e.length>1&&(contentDiv.append(backFoloderHtml),(backfoloderDiv=contentDiv.children().last()).click(function(){markBooks.getFolder(e.slice(0,-1))[e[e.length-2]].itemDiv.click()}));let o=i[l].folders;for(let e in o){o[e].itemDiv.toggle()}let r=i[l].folderDiv;if(r){""==r[0].style.backgroundColor?r[0].style.backgroundColor="#fff":r[0].style.backgroundColor="";let e=r[0].firstElementChild.nextElementSibling;"none"==e.style.display?e.style.display="block":e.style.display="none"}}function folderClick(e){let t=e.data.path,l=JSON.parse(t),i=JSON.parse(e.data.path);if(JSON.stringify(activePath)!=t)0!=activePath.length&&(toggleFolder(activePath),"edit"==homeState&&(setAllCheck(!0),checkFiles=[],checkFolders=[])),toggleFolder(l),activePath=i,"edit"==homeState&&setAllCheck();else if("edit"==homeState){let e=l[l.length-1];editFolderPage.find("input").prop("value",e),editFolderPath=l,switchPage(editFolderPage)}}function fileClick(e){if(!("svg"==e.target.localName|"button"==e.target.localName|"path"==e.target.localName))if("edit"==homeState){let t=e.data.file.name,l=e.data.file.url;editFileData=e.data.file,$("#fileNameInput").prop("value",t),$("#fileUrlInput").prop("value",l),switchPage(editFilePage)}else window.open(e.currentTarget.getAttribute("title"),"newwindow"+e.currentTarget.getAttribute("title"))}function addFile(){let e=addUrlInput.val().trim(),t=addNameInput.val().trim(),l=pathInput.val().trim();e&&t&&l?(t.length>100&&(t=t.substring(0,100)),l.length>100&&(l=l.substring(0,900)),e.length>2e3?showInfo("填写错误","url过长"):0==e.indexOf("http")?userToken&&addFilesNet([{name:t,url:e,folder:l.split(">")}],function(e,t){e?(importFiles(t),clickFirst(),showInfo("添加成功","添加成功!")):showInfo("添加失败","添加失败!")}):showInfo("填写错误","网址要填写完整包括http(s)://...")):showInfo("填写错误","网址 名字 收藏文件 不能为空")}function addFilesNet(e,t){let l=JSON.stringify(e,function(e,t){if("fileDiv"!==e||null===t)return t});(l=encodeURIComponent(l))&&request(`token=${userToken}&files=${l}`,url+"/index.php/index/index/addMarkBooks",function(e){saveResInfo(e);let l=JSON.parse(e.currentTarget.response);if("code"in l&"data"in l&"200"==l.code){let e=l.data;return t(!0,e)}return t(!1)},"post")}function addFileLocal(e,t){let l=markBooks.getFolder(e),i=e[e.length-1];null==l&&(l=addFolderLocal(e)),JSON.stringify(e)==JSON.stringify(activePath)&&addFileView(t),l[i].files.push(t),setFolderMargin()}function addFileView(e){let t=e.url,l=t.replace(t.replace(/(http|https):\/\/(www.)?(\w+(\.)?)+\/?/,""),""),i=fileHtml.replace("{{icon}}",l+"/favicon.ico");i=(i=(i=i.replace(/{{name}}/g,e.name)).replace("{{i}}",e.i)).replace("{{url}}",e.url);let n=contentDiv.children().last(),o=$(i);"back"==n.attr("tag")?n.before(o):contentDiv.append(o),o.on("click",{file:e},fileClick);let r=o.children().last();"edit"==homeState&&r.show(),o.find("input").on("click",{data:e,type:"file"},checkClick),e.fileDiv=o}function parseMarkBooks(e){let t=!1,l=[];l.push("其他");let i=[],n="",o="";const r=new htmlparser.Parser({onopentag(e,l){o=e,"h3"===e&&"last_modified"in l?t=!0:"a"===e&&"href"in l?(n=l.href,t=!0):t=!1},ontext(e){if(t){if("h3"==o&&(""!=e.trim()?l.push(e):l.push("未命名")),"a"==o){l[l.length-1]||console.log("error"),""==e.trim()&&(e="未命名");let t=[];Object.assign(t,l),i.push({name:e,url:n,folder:t}),o=""}t=!1}},onclosetag(e){"dl"==e&&l.pop()}});return r.write(e),r.end(),i}function sync(e){var t="",l=[];return(l=markBooks.getAllFiles([])).sort(function(e,t){return e.i-t.i}),t=JSON.stringify(l,function(e,t){return"folderDiv"==e|"fileDiv"==e|"itemDiv"==e|"ico"==e|"create_time"==e|"user"==e|"update_time"==e?void 0:t}),t=MD5(t),request("mail="+userMail+"&token="+userToken+"&feature="+t,url+"/index.php/index/index/sync",function(t){saveResInfo(t);let l=JSON.parse(t.currentTarget.response);if("code"in l&"data"in l&"200"==l.code){markBooks.deleteAll(),foldersDiv.html(""),contentDiv.html(""),activePath=[],importFiles(l.data),saveMarkBook(),console.log("sync"),clickFirst(),e&&e()}},"post"),t}function importHtmlMB(e){var t=e.target.files[0];if(t){var l=new FileReader;l.readAsDataURL(t),l.onload=function(e){let t=this.result.replace(/^[^,]*,/,""),l=binaryToUtf8(window.atob(t)),i=parseMarkBooks(l);l.length>23e5?showInfo("导入结果","书签太多了,精简到2M以下吧",!0):0!=i.length?(showInfo("正在导入","正在导入书签需要一会",!1),addFilesNet(i,function(e,t){e?(importFiles(t),clickFirst(),showInfo("导入成功","成功导入!")):showInfo("导入失败","导入失败!")})):showInfo("导入结果","未在该文件发现书签,请选择浏览器导出的HTML文件",!0)}}}function importFiles(e){for(let t=0;t<e.length;t++){addFileLocal(JSON.parse(e[t].folder),e[t])}}function outputBody(e=null,t=null){let l="",i=[],n={};null==e?(e=markBooks,l="root",n=markBooks.folders):(l=t,i=e.files,n=e.folders);let o=mBsFolderHtml.replace("{{folderName}}",l);for(let e in i){let t=mBsfileHtml.replace("{{url}}",i[e].url);o+=t=t.replace("{{name}}",i[e].name)}for(let e in n)o+=outputBody(n[e],e);return o+="</DL><p>"}function outputHtmlMB(){let e=outputBody();const t=mBsHtml.replace("{{folders}}",e),l=new Blob([t],{type:"text/plain;charset=utf-8"}),i=URL.createObjectURL(l),n=document.createElement("a");n.href=i,n.download="markbook.html",n.click(),URL.revokeObjectURL(i),showInfo("导出成功","书签已经全部导出  下载完成！")}function clickFirst(){if(!("folders"in markBooks))return;let e=markBooks.folders,t=Object.keys(e);if(t.length>0){e[t[0]].folderDiv.trigger("click")}}function setFolderMargin(){if("none"==homePage.css("display"))return;let e=mbLeft.height(),t=foldersDiv.height();t<e?foldersDiv.css("margin-top",e-t+"px"):foldersDiv.css("margin-top","unset")}function dropdown(e){let t=e.currentTarget.nextSibling.nextSibling;"none"!=t.style.display&&t.style.display?t.style.display="none":(t.style.display="block",pathInput.val(""),setSelect())}function selectClick(e){let t=pathInput.val().trim();t=""!=t?pathInput.val()+">"+e:e,pathInput.val(t);let l=markBooks.getFolder(t.split(">"))[e].folders;0!=Object.keys(l).lenght&&setSelect(l)}function setSelect(e=null){folderSelectDiv.html(""),null==e&&(e=markBooks.folders),e&&Object.keys(e).forEach(e=>{folderSelectDiv.append(folderSelectHtml.replace("{{folderName}}",e)),folderSelectDiv.children().last().click(()=>{selectClick(e)})})}$(document).ready(function(){if(findE(),pageList.push(homePage),setEvent(),userToken=getCookie("token"),!(userMail=getCookie("mail")))return void switchPage(loginPage);userMailP.text(userMail),setCookie("mail",userMail,100),setCookie("token",userToken,100);let e=window.localStorage.mark_books;!!e&!!getCookie("mail")&&importFiles(JSON.parse(decodeURIComponent(e)));sync(),(searchEngine=getCookie("engine"))?setCookie("engine",searchEngine,999):searchEngine="baidu",searchEngine in engineUrls||(searchEngine="baidu"),clickFirst(),setFolderMargin(),window.onresize=setFolderMargin}),window.onerror=function(e,t,l,i,n){let o="";if(n&&n.stack)o=n.stack.toString();else if(arguments.callee){for(var r=[],a=arguments.callee.caller,s=3;a&&--s>0&&(r.push(a.toString()),a!==a.caller);)a=a.caller;r=r.join(","),o=n.stack.toString()}let c="  browser: "+window.navigator.userAgent+" errMsg: "+e+"  in: "+t+"  lineNum: "+l+" any: "+o+"  response:  "+responseInfo,d=encodeURIComponent(c.slice(0,4e3));request(`mail=${userMail}&info=${d}`,"/index.php/index/index/jsError",function(e){},"post")};