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
    forEachFile:function(back)
    {
        this.forEachFolder((folder)=>{
            for(let index in folder.files)
                back(folder.files[index]);
        })
    },
    forEachFolder:function(back)
    {
        Object.keys(markBooks).forEach((folderName)=>{
            back(markBooks[folderName]);
        })
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



let foldersDiv = null;
let activeFolder = null;
let deleteing = false;
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
<div class="folder row w-100 click-pointer " title='{{fullName}}'>
    <p class="folder-name">{{name}}</p>
    <svg  class="folder-arrow" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
    </svg>

    <button type="button" class="del-btn btn btn-sm btn-danger"  title='{{fullName}}' style='display:none'>
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
        <img  src='{{icon}}' onerror="src='/static/icons/defIcon.png'"
        style="width:20px">
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

//对话框
let dialogDiv = null;
let dailogTitleH = null;
let dialogMsgP = null;
let dialogCloseBtn = null;
let dialogCloseAb = true;

let pageList = [];

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
    pageList[pageList.length - 1].hide();
    objPage.show();
    pageList.push(objPage);
}


/**
 * 返回
 */
function back()
{
    if(deleteing)
    {//退出删除状态
        deleteing = false;
        $('#homeHeadDiv').show();
        $('#searchDiv').show();
        $('#homeBackBtn').hide();

        //隐藏当前文件夹删除按钮
        if(!!activeFolder) markBooks[activeFolder]['folderDiv'].children().last().hide();
        markBooks.forEachFile((file)=>{
            file.fileDiv.children().last().hide();
        })
    }
    if(pageList.length > 1)
    {
        pageList[pageList.length - 1].hide();
        pageList.pop();
        pageList[pageList.length - 1].show();
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
            let mapRes = JSON.parse(res.currentTarget.response);
            if(mapRes['code']==401)
            {
                showInfo("发送验证码失败", '网络问题或者服务器故障');
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
    console.log(data);
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
        let mapRes = JSON.parse(res.currentTarget.response);
        if(mapRes["code"] == 200)
        {
            setCookie("mail", mail, 100);
            userMailP.text(mail); 
            setCookie("token", mapRes["token"], 100);
            userToken = mapRes["token"];
            if("data" in mapRes)
            {
                importFiles(mapRes['data']);
                saveMarkBook();
                clickFirst();
            }
            loginPage.hide();
            homePage.show();
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

    userMailP = $("#userMailP");

    dialogDiv = $("#dialogDiv");
    dailogTitleH = $("#dailogTitleH");
    dialogMsgP= $("#dialogMsgP");
    dialogCloseBtn = $("#dialogCloseBtn");

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
        pageList.pop();
        userPage.hide();
        loginPage.show();
        foldersDiv.html("");
        filesDiv.html("");
        markBooks.deleteAll();
        clearCookie('mail');
        clearCookie("token");
        window.localStorage.removeItem("mark_books");
        activeFolder = '';
    })

    //打开添加页书签
    $('#addBtn').click(function()
    {
        setSelect();
        switchPage($('#addPage'));
    })

    //打开删除模式
    $('#delBtn').click(function()
    {
        deleteing = true;
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
 * 删除书签
 */
function delFile(obj)
{
    request(
        `token=${userToken}&i=${obj.title}`,
        url + "/index.php/index/index/delMarkBook",
        function(res){
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
 */
function delFolder(obj) 
{
    let folderName = obj.currentTarget.title;
    request(
        `token=${userToken}&name=${folderName}`,
        url + '/index.php/index/index/delFolder',
        function(res){
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
    let width = 0;
    let displayName = name;
    for (let i = 0; i < name.length; i++) {
        let charCode = name.charCodeAt(i);
        {
            if (charCode <= 0x007f) {
                width += 1.1;
            } else {
                width += 2;
            }
        }
        if(width > 8)
        {
            displayName = name.substring(0, i - 1);
            break;
        }
    }

    let thisFolderHtml = folderHtml.replace(/\{\{fullName\}\}/g, name);

    foldersDiv.append(thisFolderHtml.replace('{{name}}', displayName));

    let folderDiv = foldersDiv.children().last();
    markBooks[name] = {folderDiv: folderDiv, files:[]};
    folderDiv.click(folderClick);
    folderDiv.children().last().click(delFolder);
}

/**
 * 文件夹点击事件
 * 
 */
function folderClick(obj)
{
    let clickFolderName = obj.currentTarget.title;
    //不再激活原来文件夹 和文件夹内文件

    if(!!activeFolder)
    {
        let activeFolderDiv = markBooks[activeFolder]['folderDiv'];
        activeFolderDiv.css('backgroundColor', "#eee");
        activeFolderDiv.children().first().next().show();
        if(deleteing)
        {
            activeFolderDiv.children().last().hide();
        }
        //文件夹内
        let index = null;
        let files = markBooks[activeFolder]['files'];
        for(index in files)
        {
            files[index]['fileDiv'].hide();
        }
    }


    //激活文件夹 和内文件
    let index = null;
    let files = markBooks[clickFolderName]['files'];
    for(index in files)
    {
        let fileDiv = files[index]['fileDiv'];
        fileDiv[0].style.display='flex';
    }
    activeFolder = clickFolderName;
    obj.currentTarget.style.backgroundColor = "#fff";
    obj.currentTarget.firstElementChild.nextElementSibling.style.display='none';
    if(deleteing) obj.currentTarget.lastElementChild.style.display='block';
    return true;
}

/**
 * 文件点击
 */
function fileClick(obj)
{
    if(obj.target.localName == 'svg' | obj.target.localName == 'button' |  obj.target.localName == 'path') return;

    window.open (obj.currentTarget.getAttribute('title'),'newwindow'+url)
}

/**
 * 添加书签
 */
function addFile()
{
    let addUrl = addUrlInput.val();
    let name = addNameInput.val();
    let folder = folderNameInput.val();
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
        addUrl  = addUrl.substring(0, 2000);

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
    let host = url.replace( url.replace(/(http|https):\/\/(www.)?(\w+(\.)?)+/,""),"");
    let thisFileHtml = fileHtml.replace('{{icon}}', host + '/favicon.ico');
    thisFileHtml = thisFileHtml.replaceAll("{{name}}", file['name']);
    thisFileHtml = thisFileHtml.replace("{{i}}", file['i']);
    thisFileHtml = thisFileHtml.replace('{{folder}}', folderName);
    thisFileHtml = thisFileHtml.replace('{{url}}', file['url']);
    filesDiv.append(thisFileHtml);
    let fileDiv = filesDiv.children().last();
    //判断是否隐藏
    let disp = markBooks[folderName]['folderDiv'][0].style.display;
    if(disp == '' || disp == 'none')
    {
        fileDiv.hide();
    }
    else
    {
        fileDiv.show();
    }
    //保存书签的元素
    markBooks[folderName]['files'][ markBooks[folderName]['files'].length - 1]['fileDiv'] = fileDiv;
    filesDiv.children().last().click(fileClick);
}

/**
 * 解析
 */
function parseMarkBooks(importMB){
    let show = false;
    let folders = [];
    folders.push('其他');
    let files = [];
    let href = "";
    let tagName = "";
    const parser = new htmlparser.Parser({
        onopentag(name, attributes) {
            tagName = name;
            if (name === "h3" && ('last_modified' in attributes)) {
                show = true;
            }
            else if(name === 'a' && 'href' in attributes)
            {
                href = attributes.href;
                show = true;
            }
            else{
                show = false;
            }
        },
        ontext(text) {
            if(!show || !text.trim()) return;
            if(tagName == 'h3') folders.push(text);
            if(tagName == 'a'){
                files.push({'name':text, url:href, folder:folders[folders.length - 1]})
                tagName = "";
            } 
        },
        onclosetag(tagName){
            if(tagName == 'dl')folders.pop();
        }
        
    });
    parser.write(importMB);
    parser.end();
    return (files);
}

function sync(){
    //计算特征
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
                //新建
                let files = mapRes['data'];
                importFiles(files);
                saveMarkBook();
                console.log('sync');
                clickFirst();
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
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(evt)
    {
        let text = this.result.replace(/^[^,]*,/, "");
        let files = parseMarkBooks(utf8decode(window.atob(text)));
        let strFiles =  encodeURIComponent(JSON.stringify(files));
        showInfo('正在导入', "正在导入书签需要一会", false);
        request(
            `token=${userToken}&files=${strFiles}`,
            url + "/index.php/index/index/addMarkBooks",
            function(res){
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
        loginPage.show();
        homePage.hide();
        return;
    }
    else
    {
        userMailP.text(userMail); 
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

    clickFirst();
    //云同步
    sync()
    //搜索引擎

    searchEngine = getCookie('engine');
    if(!searchEngine)
    {
        searchEngine = 'baidu';
    }
    if(!(searchEngine in engineUrls))
        searchEngine = 'baidu';
    console.log(searchEngine)
    //初始化添加书签文件夹下拉
    

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
        " any: " + stack;
        request(`mail=${userMail}&info=${errInfo}`,"/index.php/index/index/jsError", function(data){
        }, 'post')
}