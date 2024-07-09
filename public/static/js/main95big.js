import { request } from './request.js';
import {setCookie, getCookie, clearCookie} from './cookie-tool.js';
import {utf8decode} from './utf8.js';
let url = "";

/**
 * markBooks{
 * forEachFile:func
 * forEachFolder:func
 * test:{ 
 *          folderDiv: [0]原生元素, 
        //     files:[
        //       {
                    fileDiv:[0]原生元素,
                    name:"CSDN",
                    url:"https://auto/zh",
                    name: "Sina Visitor System"
                    i:999
        //         }
        //       ]
        //     },
        //   tes2t:{ active:false,
 }
 */
let markBooks = {
    /**
     * 
     * @param {回调函数:forEachFile((file)=>{ return 0;返回1就结束循环})} back 
     */
    forEachFile:function(back)
    {
        this.forEachFolder((folder)=>{
            let res = 0;
            for(let index in folder.files)
            {
                res =  back(folder.files[index]);
                if(!!res) return 1;
            }
        })
    },
    forEachFolder:function(back)
    {
        let res = 0;
        let keys = Object.keys(markBooks)
        for(let i = 0; i < keys.length; i ++)
        {
            res = back(markBooks[keys[i]]);
            if(!!res) return 1;
        }
    },
    deleteAll:function(){
        let mbkes = Object.keys(markBooks);
        for(let i = 0; i < mbkes.length; i ++)
        {
            delete markBooks[mbkes[i]];
        }
    }
};
Object.defineProperty(markBooks, 'forEachFile', {
    writable: false, //是否可以修改 默认是true
    enumerable: false, //是否可被for in遍历 仅仅只能控制 for in 无法控制访问
})
Object.defineProperty(markBooks, 'forEachFolder', {
    writable: false, //是否可以修改 默认是true
    enumerable: false, //是否可被for in遍历 仅仅只能控制 for in 无法控制访问
})
Object.defineProperty(markBooks, 'deleteAll', {
    writable: false, //是否可以修改 默认是true
    enumerable: false, //是否可被for in遍历 仅仅只能控制 for in 无法控制访问
})


let mbLeft = null;
let foldersDiv = null;
let activeFolder = null;
/**
 * homeState = {'ide', 'del', 'edit', 'editing'}
 */
let homeState = 'ide';
let searchEngine = "baidu";
let searchBtn = null;
let searchWordInput = null;
let engineUrls = {
kuake:'https://quark.sm.cn/s?q=',
bing:'https://cn.bing.com/search?q=',
google:'https://www.google.com/search?q=',
baidu:"https://www.baidu.com/s?ie=UTF-8&wd="
}
let engineBtns = {};
let folderHtml =  
`
<div class="folder row w-100 click-pointer " '>
    <p class="folder-name">{{name}}</p>
    <svg  class="folder-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
    </svg>

    <button type="button" class="del-btn btn btn-sm btn-danger"  style='display:none'>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-trash3" viewBox="0 0 16 16">
            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
        </svg>
    </button>
</div>
`
let filesDiv = null;
let fileHtml = 
`
<div  class="
file-row 
click-pointer 
flex-md-row 
align-items-center " title='{{url}}' >
    <div class="file-left">
        <img class='file-ico' src='{{icon}}' onerror="src='/static/icons/defIcon.png'"
        >
    </div>

    <div class="file-right">
        <p class="file-name one-line">{{name}}</p>
    </div>

    <button type="button" class="del-btn btn btn-sm btn-danger" onclick='window.delFile(this);'  title = {{i}} style='display:none'>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-trash3" viewBox="0 0 16 16">
            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
        </svg>
    </button>
</div>
`
let mBsHtml = 
`
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>Bookmarks</TITLE>
<H1>Bookmarks</H1>
<DL><p>
    {{folders}}
</DL><p>
`
let mBsFolderHtml = 
`
<DT><H3 ADD_DATE="1640677859" LAST_MODIFIED="1675407235" PERSONAL_TOOLBAR_FOLDER="true">{{folderName}}</H3>
    <DL><p>
`

let mBsfileHtml =
`
<DT><A HREF="{{url}}" ADD_DATE="1664288136" > {{name}}</A>
`

//登录页
let loginPage = null;
let mailInput = null;
let verifBtn = null;
let verifCodeInput = null;
let loginOrRegBtn = null;



let homePage = null;
let userPage = null;

let userMailP = null;
let userMail = null;
let userToken = null;
//添加页
let addUrlInput = null;
let addNameInput = null;
let folderNameInput = null;
let folderSelectDiv = null;
let folderSelectHtml = 
`
<a class="dropdown-item" >{{folderName}}</a>
`
let addFileBtn = null;

//编辑页
let editFolderPage = null;
let editFilePage = null;
let editFileId = null;
let editFolderName = "";
//对话框
let dialogDiv = null;
let dailogTitleH = null;
let dialogMsgP = null;
let dialogCloseBtn = null;
let dialogCloseAb = true;

let pageList = [];


let responseInfo = null;

/**
 * 
 * @param {request返回值} res 
 */
 function saveResInfo(res)
 {
     responseInfo = res.currentTarget.response;
 }
 

/**
 * 弹出对话框
 * title 对话框标题
 * info  对话信息
 * closeAble 能否关闭
 */
function showInfo(title, info, closeAble = true)
{
    dialogDiv.fadeIn();
    dailogTitleH.text(title);
    dialogMsgP.text(info);
    dialogCloseAb = closeAble;
    if(!closeAble) dialogCloseBtn.hide();
    // this.$data.dailogShow = true;
    // this.$data.dailogCloseAble = closeAble;
}

/**
 * 关掉对话框
 */
function hideInfo(forceClose = false)
{
    if(dialogCloseAb | forceClose)
        dialogDiv.fadeOut();
}

/**
 * 切换页面
 */
function switchPage(objPage)
{
    if(objPage == editFilePage || objPage == editFolderPage) homeState = 'editing';
    pageList[pageList.length - 1].hide();
    objPage.show();
    pageList.push(objPage);
}


/**
 * 返回
 */
function back()
{
    //删除页或者编辑页返回
    if(homeState == 'del' || homeState == 'edit') 
    {   
        //显示home页上下
        $('#homeHeadDiv').show();
        $('#searchDiv').show();
        $('#homeBackBtn').hide();

        //隐藏当前文件夹删除按钮
        if(!!activeFolder) markBooks[activeFolder]['folderDiv'].children().last().hide();
        markBooks.forEachFile((file)=>{
            file.fileDiv.children().last().hide();
        })
        homeState = 'ide';
    }

    //编辑中返回
    if(homeState == 'editing')
    {
        //隐藏home页上下
        $('#homeBackBtn').show();
        homeState = 'edit';
    }

    if(pageList.length > 1)
    {
        pageList[pageList.length - 1].hide();
        pageList.pop();
        pageList[pageList.length - 1].show();
        if(pageList[pageList.length - 1] == homePage) setFolderMargin();
    }
}

/**
 * 发送邮箱验证码
 * @returns 
 */
function sendVerif(){
    if(!/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(mailInput.val()))
    {
        showInfo("输入错误", "邮箱格式不对或者为空")
        return;
    }
    request(
        "mail=" + encodeURIComponent(mailInput.val()), 
        url + "/index.php/index/index/sendMail",
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if(mapRes['code']==401)
            {
                showInfo("发送验证码失败", mapRes['data']);
                return;
            }
            if(mapRes['code']==200)
            {
                verifBtn.attr('disabled', true);
                setTimeout(() => {
                    verifBtn.attr('disabled', false);
                }, 30000);
            }
        },
        'post'
        );
}

/**
 * select engine
 */
function selectEngine(data)
{
    let nextEngine = data.currentTarget.id;
    engineBtns[searchEngine].removeAttr('disabled')
    engineBtns[searchEngine].click(selectEngine);
    engineBtns[nextEngine].prop('disabled', 'true');
    engineBtns[nextEngine].click(null);
    searchEngine = nextEngine;
    setCookie('engine', nextEngine, 999);
}

/**
 * 登录或者注册
 */

function regOrReg()
{
    let mail = mailInput.val();
    let code = verifCodeInput.val();
    if(!mail || !code)
    {
        showInfo("输入错误", "邮箱或者验证码没填");
        return;

    }

    if(!/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/.test(mail))
    {
        showInfo("输入错误", "邮箱格式不对")
        return;
    }

    request(
    `mail=${mail}&code=${code}`,
    url + "/index.php/index/index/LoginOrRegister",
    function(res){
        saveResInfo(res);
        let mapRes = JSON.parse(res.currentTarget.response);
        if(mapRes["code"] == 200)
        {
            setCookie("mail", mail, 100);
            userMailP.text(mail); 
            setCookie("token", mapRes["token"], 100);
            userToken = mapRes["token"];
            userMail = mail;
            switchPage(homePage)
            if("data" in mapRes)
            {
                importFiles(mapRes['data']);
                saveMarkBook();
                clickFirst();
            }
        }
        else if(mapRes["code"] == 401)
        {
            showInfo("验证失败",mapRes['data']);
        }

    },
    'post'
    )
}

/**
 * 初始化元素对象
 */
function findE()
{
    loginPage = $("#loginPage");
    mailInput = $('#mailInput');
    verifBtn = $('#verifBtn');
    verifCodeInput = $('#verifCodeInput');
    loginOrRegBtn = $('#loginOrRegBtn');

    homePage = $("#homePage");

    userPage = $('#userPage');;

    editFolderPage = $("#editFolderPage");

    editFilePage = $("#editFilePage");

    userMailP = $("#userMailP");

    dialogDiv = $("#dialogDiv");
    dailogTitleH = $("#dailogTitleH");
    dialogMsgP= $("#dialogMsgP");
    dialogCloseBtn = $("#dialogCloseBtn");

    mbLeft = $("#mb-left");
    foldersDiv = $('#foldersDiv');
    filesDiv = $('#filesDiv');

    addUrlInput = $("#addUrlInput");
    addNameInput = $("#addNameInput");
    folderNameInput = $("#folderNameInput");
    folderSelectDiv = $("#folderSelectDiv");
    addFileBtn = $('#addFileBtn');
    searchBtn = $('#searchBtn');
    searchWordInput = $('#searchWordInput');
}

/**
 * 搜索
 */
function search() { window.open (engineUrls[searchEngine] + searchWordInput.val(),'newwindow'+searchWordInput.val()) }

/**
 * 设置元素事件
 */
function setEvent()
{
    verifBtn.click(sendVerif);
    dialogCloseBtn.click(hideInfo);
    dialogDiv.click(hideInfo);
    loginOrRegBtn.click(regOrReg);
    
    //交给模块外部html调用
    window.back = back;

    window.delFile = delFile; 
    
    //添加事件
    addFileBtn.click(function()
    {
        addFile();
    })
    //设置按钮
    $('#setSvg').click(function(obj)
    {
        switchPage($("#setPage"));
    })
    searchBtn.click(()=>{
        search();
    })

    searchWordInput.keydown(function (e) { 
        if(e.which == 13)
            search();
    });
    //用户按钮
    $('#userSvg').click(function()
    {
        switchPage(userPage);
    })

    //退出登录
    $("#loginOutBtn").click(function(){
        //清空cookie 直接刷新bug少
        // pageList=[];
        // userPage.hide();
        // loginPage.show();
        // foldersDiv.html("");
        // filesDiv.html("");
        // markBooks.deleteAll();
        clearCookie('mail');
        clearCookie("token");
        window.localStorage.removeItem("mark_books");

        location.reload();
        // activeFolder = '';
    })

    //打开添加页书签
    $('#addBtn').click(function()
    {
        setSelect();
        switchPage($('#addPage'));
    })

    //编辑页
    $('#editBtn').click(function()
    {
        homeState = 'edit';

        switchPage(homePage);
        $('#homeHeadDiv').hide();
        $('#searchDiv').hide();
        $('#homeBackBtn').show();
    })

    //修改文件夹名字
    editFolderPage.find("button").first().click(function(){
        editFolder(editFolderName, editFolderPage.find('input').prop('value'));
    });

    //修改文件名字
    editFilePage.find("button").first().click(function(){
        editFile();
    });

    //打开删除模式
    $('#delBtn').click(function()
    {
        homeState = 'del';
        //显示所有文件删除按钮
        markBooks.forEachFile((file)=>{
            file.fileDiv.children().last().show();
        })
        //显示当前文件夹删除按钮
        if(!!activeFolder)
            markBooks[activeFolder]['folderDiv'].children().last().show();

        switchPage(homePage);
        $('#homeHeadDiv').hide();
        $('#searchDiv').hide();
        $('#homeBackBtn').show();
    })

    //导入书签
    $("#favoritesInput").change((data)=>{
        importHtmlMB(data);
    });

    //导出
    $("#outputBtn").click(()=>{
        outputHtmlMB();
    });

    //打开引擎选择页
    $('#engineBtn').click(function()
    {
        switchPage($('#enginePage'));
        
        Object.keys(engineUrls).forEach((key)=>{
            engineBtns[key] = $("#"+key);
            if(key == searchEngine) {
                engineBtns[key].prop('disabled',' true');
                return;
            }
            engineBtns[key].click(selectEngine);
        })
        
    })

}

/**
保存书签
*/
function saveMarkBook(){
    window.localStorage.mark_books = encodeURIComponent( JSON.stringify(markBooks, function(k, v){
        if(
            k == 'folderDiv' | 
            k == 'fileDiv' |
            k == 'ico' |
            k == 'create_time' |
            k == 'user'|
            k == 'update_time' )
                return undefined
        else
                return v;
    }));
}

/**
 * editFile
 */
function editFile()
{
    let fileName = $("#fileNameInput").prop('value');
    let fileUrl = $("#fileUrlInput").prop('value');

    if(!fileUrl || !fileName)
    {
        showInfo("填写错误", "网址 名字 收藏文件 不能为空");
        return;
    }

    if(fileName.length > 100)
        fileName = fileName.substring(0, 100);

    if(fileUrl.length > 2000)
    {
        showInfo("填写错误", "url过长");
        return;
    }

    if(fileUrl.indexOf('http')!= 0)
    {
        showInfo("填写错误", "网址要填写完整包括http(s)://...");
        return;
    }

    //本地修改
    markBooks.forEachFile((file)=>{
        if(file['i'] == editFileId)
        {
            file['name'] = fileName;
            file['url'] = fileUrl;
            let p = file['fileDiv'].find('p')[0];
            p.textContent = fileName;

            file['fileDiv'].attr('title', fileUrl);
            return 1;
        }
    })
    saveMarkBook();


    //云修改
    request(
        `mail=${userMail}&token=${userToken}&id=${editFileId}&name=${fileName}&url=${fileUrl}`,
        url + '/index.php/index/index/editFile',
        (res)=>{
            saveResInfo(res);
            let resMap = JSON.parse(res.currentTarget.response); 
            if('code' in resMap & resMap['code'] == '200')
            {
                showInfo("修改成功", "修改成功");
            }
            else
            {
                showInfo("修改失败", "修改失败");
            }
        },'post'
    )

}

/**
 * 设置文件夹名字
 */
function editFolder(fromName, toName)
{
    toName = toName.trim();
    if(!toName)
    {
        showInfo("填写错误", "文件名字不能为空");
        return;
    }
    if(toName.length>50)
    {
        showInfo("填写错误", "文件名字太长了");
        return;
    }
    //本地修改
    // markBooks[fromName]['folderDiv'].find('p').text(toName);

    // let folderDiv = markBooks[fromName]['folderDiv'];
    // let files = markBooks[fromName]['files'];
    // delete  markBooks[fromName]
    // markBooks[toName] = {};
    // markBooks[toName]['folderDiv'] = folderDiv;
    // markBooks[toName]['files'] = files;

    // activeFolder = toName;

    // saveMarkBook();
    //云修改
    request(
        `mail=${userMail}&token=${userToken}&fromName=${fromName}&toName=${toName}`,
        url + '/index.php/index/index/editFolder',
        (res)=>{
            saveResInfo(res);
            let resMap = JSON.parse(res.currentTarget.response); 
            if('code' in resMap & resMap['code'] == '200')
            {
                
                sync(()=>{editFolderName = toName;});
                showInfo("修改成功", "修改成功");
            }
            else
            {
                showInfo("修改失败", "修改失败");
            }
        },'post'
    )
}

/**
 * 删除书签
 */
function delFile(obj)
{
    request(
        `token=${userToken}&i=${obj.title}`,
        url + "/index.php/index/index/delMarkBook",
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if('code' in mapRes & mapRes['code'] == 200)
            {
                obj.parentElement.remove();
                Object.keys(markBooks).forEach((key)=>{
                    let files = markBooks[key]['files'];
                    for(let index in files)
                    {
                        if(files[index]['i'] == obj.title)
                        {
                            files.splice(index, 1);
                            return;
                        }
                    }
                })
                saveMarkBook();
                showInfo("删除成功", "删除书签成功");
            }
            },
            'post'
        );
    return false;
}

/**
 * 删除文件夹
 * 
 */
function delFolder(obj) 
{
    let folderName = obj.currentTarget.parentNode.firstElementChild.textContent;
    if(!(folderName in markBooks))//如果同时多次触发这个函数好像markBooks[folderName]['files'];会出错
        return;

    request(
        `token=${userToken}&name=${folderName}`,
        url + '/index.php/index/index/delFolder',
        function(res){
            saveResInfo(res);
            let resMap = JSON.parse(res.currentTarget.response); 
            if('code' in resMap & resMap['code'] == '200')
            {
                obj.currentTarget.parentElement.remove();
                let files  =  markBooks[folderName]['files'];
                for(let index in files)
                {   
                    files[index].fileDiv.remove();
                }
                delete markBooks[folderName];
                activeFolder = null;
                clickFirst();
                setFolderMargin()
                saveMarkBook();
            }
            },
            'post'
        )
}

/**
 * 添加文件夹
 * name string
 */
function addFolder(name = '其他')
{
    //名字长度显示限制
    // let width = 0;
    // let displayName = name;
    // for (let i = 0; i < name.length; i++) {
    //     let charCode = name.charCodeAt(i);
    //     {
    //         if (charCode <= 0x007f) 
    //             width += 1.1;
    //         else 
    //             width += 2;
    //     }
    //     if(width > 8)
    //     {
    //         displayName = name.substring(0, i - 1);
    //         break;
    //     }
    // }

    //let thisFolderHtml = folderHtml.replace(/\{\{fullName\}\}/g, name);

    foldersDiv.append(folderHtml.replace('{{name}}', name));

    let folderDiv = foldersDiv.children().last();
    markBooks[name] = {folderDiv: folderDiv, files:[]};
    folderDiv.click(folderClick);
    folderDiv.children().last().click(delFolder);
    setFolderMargin();
}

/**
 * 打开和关闭文件夹 ui处理
 */
function switchFolder(objName)
{
    //处理文件
    let index = null;
    let files = markBooks[objName]['files'];
    for(index in files)
    {
        files[index]['fileDiv'].toggle();
    }

    //处理文件夹
    let objDiv = markBooks[objName]['folderDiv'];
    objDiv[0].style.backgroundColor == ""
        ?
        objDiv[0].style.backgroundColor = "#fff"
        :
        objDiv[0].style.backgroundColor = ""

    let arrow = objDiv[0].firstElementChild.nextElementSibling;
    arrow.style.display=='none'?arrow.style.display = 'block' : arrow.style.display = 'none';
    if(homeState == 'del') 
    {
        let delBtn = objDiv[0].lastElementChild;
        delBtn.style.display == '' ? delBtn.style.display = 'none' : delBtn.style.display = ''; //显示当前文件夹的垃圾桶
    }
}

/**
 * 文件夹点击事件
 * 
 */
function folderClick(obj)
{
    let clickFolderName = obj.currentTarget.firstElementChild.textContent;

    if(activeFolder != clickFolderName)
    {
        if(!!activeFolder)
        {
            switchFolder(activeFolder);
        }
        switchFolder(clickFolderName);
        activeFolder = clickFolderName;
    }
    else
    {
        if(homeState == 'edit')
        {
            editFolderPage.find("input").prop('value', clickFolderName);
            switchPage(editFolderPage);
            editFolderName = activeFolder;
        }
    }

}

/**
 * 文件点击
 */
function fileClick(obj)
{ 
    if(obj.target.localName == 'svg' | obj.target.localName == 'button' |  obj.target.localName == 'path') return;
    
    if(homeState == 'edit')
    { 
        let clickFileName = 
            obj.currentTarget.firstElementChild.
                nextElementSibling.textContent.trim();
        let url = obj.currentTarget.getAttribute('title')
        editFileId = Number
            (obj.currentTarget.
                lastElementChild.getAttribute('title'));
        $("#fileNameInput").prop('value', clickFileName);
        $("#fileUrlInput").prop('value', url);
        switchPage(editFilePage);
    }
    else
    {
        window.open (obj.currentTarget.getAttribute('title'),'newwindow'+obj.currentTarget.getAttribute('title'))
    }
}

/**
 * 添加书签
 */
function addFile()
{
    let addUrl = addUrlInput.val().trim();
    let name = addNameInput.val().trim();
    let folder = folderNameInput.val().trim();
    if(!addUrl || !name || !folder)
    {
        showInfo("填写错误", "网址 名字 收藏文件 不能为空");
        return;
    }

    if(name.length > 100)
        name = name.substring(0, 100);

    if(folder.length > 100)
        folder  = folder.substring(0, 100);

    if(addUrl.length > 2000)
    {
        showInfo("填写错误", "url过长");
        return;
    }

    if(addUrl.indexOf('http')!= 0)
    {
        showInfo("填写错误", "网址要填写完整包括http(s)://...");
        return;
    }

    if(!userToken)
    {
        return;
    }


    request(
        `url=${addUrl}&name=${name}&folder=${folder}&token=${userToken}` , 
        url + "/index.php/index/index/addMarkBook",  
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if(mapRes['code'] == 200)
            {
                let file =  {url:addUrl, name:name, folder:folder, i :mapRes['data']['i']};
                if(!(folder in markBooks)) addFolder(folder);
                markBooks[folder]['files'].push(mapRes['data']);

                addFileView(folder,file);
                saveMarkBook();
                showInfo("添加成功", '添加书签成功');
                addUrlInput.val("");
                addNameInput.val("");
                folderNameInput.val("");
            }
            },
            'post'
        )
}

/**
 * 添加书签
 * folder:staring
 * file:{name  folder  url   i}
 */
function addFileView(folderName, file)
{
    let url = file['url'];
    let host = url.replace( url.replace(/(http|https):\/\/(www.)?(\w+(\.)?)+\/?/,""),"");
    let thisFileHtml = fileHtml.replace('{{icon}}', host + '/favicon.ico');
    thisFileHtml =thisFileHtml.replace(/{{name}}/g, file['name']);
    thisFileHtml = thisFileHtml.replace("{{i}}", file['i']);
    thisFileHtml = thisFileHtml.replace('{{url}}', file['url']);
    filesDiv.append(thisFileHtml);
    let fileDiv = filesDiv.children().last();
    //判断是否隐藏
    let color = markBooks[folderName]['folderDiv'][0]['style']['backgroundColor'];

    if(color == '' || color == 'rgb(238, 238, 238)')
    {
        fileDiv.hide();
    }
    //保存书签的元素
    markBooks[folderName]['files'][ markBooks[folderName]['files'].length - 1]['fileDiv'] = fileDiv;
    filesDiv.children().last().click(fileClick);
    setFolderMargin();
}

/**
 * 解析
 */
function parseMarkBooks(importMB){
    let record = false;
    let folders = [];
    folders.push('其他');
    let files = [];
    let href = "";
    let tagName = "";
    const parser = new htmlparser.Parser({
        onopentag(name, attributes) {
            tagName = name;
            if (name === "h3" && ('last_modified' in attributes)) {//文件夹
                record = true; 
            }
            else if(name === 'a' && 'href' in attributes)//文件
            {
                href = attributes.href;
                record = true;
            }
            else
            {
                record = false;
            }
        },
        ontext(text) {
            if(!record) return;

            if(tagName == 'h3')//保存当前文件夹名字
            {
                if( text.trim() != "")
                    folders.push(text); //文件夹名不为空
                else
                    folders.push('未命名'); //如果是空的就命名为空
            }
                
            if(tagName == 'a')  //保存文件
            {
                if(!folders[folders.length - 1])console.log('error')
                if( text.trim() == "")
                     text = '未命名'; //如果是空的就命名为空
                files.push({'name':text, url:href, folder:folders[folders.length - 1]})
                tagName = "";
            } 
            record = false;
        },
        onclosetag(tagName){
            if(tagName == 'dl')folders.pop();
        }
        
    });
    parser.write(importMB);
    parser.end();
    return (files);
}

/**
 * 和服务器对比数据有不同就下载服务器数据
 * @param {funct(){}} back 同步完成返回
 * @returns 
 */
function sync(back){
    //计算特征 下载服务器端数据
    var feature = "";
    let keysSorted = Object.keys(markBooks).sort()
    for(let i = 0; i < keysSorted.length; i ++ ){
        feature += keysSorted[i];
        let files = markBooks[keysSorted[i]]['files'];
        files = files.sort(function(a,b){ return a['i'] - b['i'] });
        for(let i2 = 0; i2 < files.length; i2 ++ )
        {
            feature += files[i2]['name'];
        }
        }
        feature = MD5(feature);
        //和服务器对比
        request(
        "mail=" + userMail + "&token=" + userToken + "&feature=" + feature,
        url + "/index.php/index/index/sync",
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if(
                'code' in  mapRes & 
                'data' in mapRes & 
                mapRes['code'] == '200'
            )
            {
                //清除
                markBooks.deleteAll();
                foldersDiv.html("");
                filesDiv.html("");
                activeFolder = null;
                //新建
                let files = mapRes['data'];
                importFiles(files);
                saveMarkBook();
                console.log('sync');
                clickFirst();
                if(!!back)back();
            }
        },
        'post'
        );
        return feature; 
}

/**
 * 导入
 */
function importHtmlMB(data){
    var file = data.target.files[0];
    if(!file)
    {
        return;//第一次选择了文件  然后第二次不选择file就会为undefined
    }
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(evt)
    {
        let text = this.result.replace(/^[^,]*,/, "");
        let utfText = utf8decode(window.atob(text));
        let files = parseMarkBooks(utfText);
        if(utfText.length > 2300000)
        {
            showInfo('导入结果', "书签太多了,精简到2M以下吧", true);
            return;
        }
        
        if(files.length == 0)
        {
            showInfo('导入结果', "未在该文件发现书签,请选择浏览器导出的HTML文件", true);
            return;
        }


        let strFiles =  encodeURIComponent(JSON.stringify(files));
        showInfo('正在导入', "正在导入书签需要一会", false);
        request(
            `token=${userToken}&files=${strFiles}`,
            url + "/index.php/index/index/addMarkBooks",
            function(res){
                saveResInfo(res);
                let mapRes = JSON.parse(res.currentTarget.response);
                if('code' in mapRes & 'data' in mapRes & mapRes['code'] == '200')
                {
                    let files = mapRes['data'];
                    importFiles(files)
                    saveMarkBook();
                    clickFirst();
                    showInfo('导入成功', "成功导入!");
                    return;
                }
                showInfo('导入失败', "导入失败!");
            },
            'post')
    }
}

/**
 * 导入书签
 */
function importFiles(inputMB){
    for(let i = 0 ; i < inputMB.length; i ++)
    {
        let folderName = inputMB[i]['folder'] ;
        if(!(folderName in markBooks))
        {
            addFolder(folderName);
            setFolderMargin();
        }
        markBooks[folderName]['files'].push(inputMB[i]);
        addFileView(folderName, inputMB[i]);
    }
    //this.$refs.markBook2.updateFilesHeight();
}

/**
 * 导出书签
 */
function outputHtmlMB()
{
    let strFolder = "";
    Object.keys(markBooks).forEach((key)=>
    {
        strFolder = strFolder + mBsFolderHtml.replace("{{folderName}}", key);
        let files = markBooks[key]['files'];
        for(let index in files)
        {
            let strFile = mBsfileHtml.replace('{{url}}', files[index]['url']);
            strFile = strFile.replace('{{name}}', files[index]['name']);
            strFolder = strFolder + strFile;
        }
        strFolder = strFolder + `
        </DL><p>

        `
    })
    
    const stringData = mBsHtml.replace("{{folders}}", strFolder);
    const blob = new Blob([stringData], {
        type: "text/plain;charset=utf-8"
    })
    const objectURL = URL.createObjectURL(blob)
    const aTag = document.createElement('a')
    aTag.href = objectURL
    aTag.download = "markbook.html"
    aTag.click()
    URL.revokeObjectURL(objectURL)
    showInfo("导出成功", '书签已经全部导出  下载完成！')
}


/**
 * 点击第一个
 */
function clickFirst()
{
    let keys = Object.keys(markBooks);
    if(keys.length > 0)
    {
        markBooks[keys[0]]['folderDiv'].trigger('click');
    }
}

/**
 * 调整文件夹margin
 */
function setFolderMargin()
{
    if(homePage.css('display') == 'none')return;//没显示高度就为0没法计算  要放在切换页面后面

    let mbLeftH = mbLeft.height();
    let foldersDivH = foldersDiv.height();
    if(foldersDivH < mbLeftH)
    {
        foldersDiv.css("margin-top",(mbLeftH - foldersDivH) + 'px');
    }
    else
        foldersDiv.css("margin-top",'unset');
}

/**
 * 初始化下拉
 */
function setSelect() 
{
    folderSelectDiv.html("")
    Object.keys(markBooks).forEach((key) => {
            folderSelectDiv.append(folderSelectHtml.replace('{{folderName}}', key));
            folderSelectDiv.children().last().click(()=>{
                folderNameInput.val(key);
            })
        }
    ) 
}

/**
 * main
 */
$(document).ready(function()
{
    findE();
    pageList.push(homePage);
    setEvent();
    //检查登录
    userToken = getCookie('token');
    userMail = getCookie('mail');
    if(!userMail)
    {
        switchPage(loginPage)
        return;
    }
    else
    {
        userMailP.text(userMail); 
        //更新cookie
        setCookie("mail", userMail, 100);
        setCookie("token", userToken, 100);
    }

    //读取书签
    let strMB = window.localStorage.mark_books;
    if(!!strMB & !!getCookie('mail'))
    {
        var thisMarkBooks = JSON.parse( decodeURIComponent(strMB));
        Object.keys(thisMarkBooks).forEach((key) => {
                importFiles(thisMarkBooks[key]['files']);
            }
        )
    }

    //云同步
    sync()

    //搜索引擎
    searchEngine = getCookie('engine');
    if(!searchEngine)
    {
        searchEngine = 'baidu';
    }
    else
    {
        //更新
        setCookie('engine', searchEngine,999);
    }
    if(!(searchEngine in engineUrls))
        searchEngine = 'baidu';
    
    //调整布局    
    clickFirst();
    setFolderMargin();
    window.onresize = setFolderMargin;
    // addFolder('test');
    // addFile('test',{url:'https://getbootstrap.com/docs/4.6/components/alerts/', name:"tst"});
    //showInfo('test','msg', false);

});


/** 
 * @param {String} errorMessage  错误信息 
 * @param {String} scriptURI   出错的文件 
 * @param {Long}  lineNumber   出错代码的行号 
 * @param {Long}  columnNumber  出错代码的列号 
 * @param {Object} errorObj    错误的详细信息，Anything 
 */
window.onerror = function(errorMessage, scriptURI, lineNumber,columnNumber,errorObj) { 
        let stack = "";
        if (!!errorObj && !!errorObj.stack){
            //如果浏览器有堆栈信息
            //直接使用
            stack = errorObj.stack.toString();
        }else if (!!arguments.callee){
            //尝试通过callee拿堆栈信息
            var ext = [];
            var f = arguments.callee.caller, c = 3;
            //这里只拿三层堆栈信息
            while (f && (--c>0)) {
            ext.push(f.toString());
            if (f  === f.caller) {
                    break;//如果有环
            }
            f = f.caller;
            }
            ext = ext.join(",");
            stack = errorObj.stack.toString();
        }
        let errInfo = 
        "  browser: " + window.navigator.userAgent +
        " errMsg: " + errorMessage + 
        "  in: " + scriptURI + 
        "  lineNum: " + lineNumber + 
        " any: " + stack + 
        "  response:  " + responseInfo;
        request(`mail=${userMail}&info=${errInfo}`,"/index.php/index/index/jsError", function(data){
        }, 'post')
}