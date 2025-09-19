
import { request } from './request.js';
import {setCookie, getCookie, clearCookie} from './cookie-tool.js';
import {binaryToUtf8} from './utf8-1.js';
import {bencode, bdecode} from './safe-base64.js';
let url = "";

/**
 * markBooks{
 * forEachFile:func
 * forEachFolder:func
 * "文件夹名":{ 
 *          folderDiv: [0]原生元素, 
 *          itemDiv: [0]原生元素, 
        //  files:
                [
        //         {
                    fileDiv:[0]原生元素,
                    name:"CSDN",
                    url:"https://auto/zh",
                    name: "Sina Visitor System"
                    i:999
        //         }
        //       ]
        //  folders:
            {
                ...
            }
        //  },
//   "文件夹名2":{ active:false,
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
    forEachFolder:function(back, obj = markBooks)
    {
        let res = 0;
        let keys = Object.keys(markBooks)
        for(let i = 0; i < keys.length; i ++)
        {
            res = back(markBooks[keys[i]]);//回调
            if(!!res) return 1;//是否结束遍历

            if(!!obj['folders'])
            {
                return this.forEachFolder(back, obj['folders']);
            }
        }
    },
    //getFile:function()
    /**
     * 
     * @param {字符数组} path  (例如['收藏夹', '视频'])
     * @returns  返回 
     *                  视频:{folderdiv:[], itemdiv:[], files:[], folders:{}}  
     *                  音频:{folderdiv:[], itemdiv:[], files:[], folders:{}}
     *                                   。。。同一级的
     *              如果找到收藏夹返回整个收藏夹  如果找不到返回null
     * 
     *          或许可以改为 视频:{'视频', folderdiv:[], itemdiv:[], files:[], folders:{}}  这种结构
     */
    getFolder:function(path)
    {
        //路径必须是数组
        if (typeof markBooks === 'undefined' ||!Array.isArray(path)) {
            return null;
        }
        if (path.length == 0) 
            return markBooks;
        // 开始时设置为最外层对象
        let obj = markBooks;  
        if(!('folders' in obj))return null;

        for (let i = 0; ; i++) 
        {
            if (! (path[i] in obj.folders)) 
            {
                // 如果中间某一步找不到，返回空
                return null;
            }

            if(i >= path.length - 1 && path[i] in obj.folders)
            {
                // 结束
                return obj.folders;
            }
            else
            {
                // 按照路径逐步深入
                obj = obj.folders[path[i]];  
            }
        }
        return obj;
    },
    /**
     * 
     * @param {数组类型} path  (例如['收藏夹', '视频'])
     * @param {对象} obj (例如 
     * {
            fileDiv:[0]原生元素,
            name:"CSDN",
            url:"https://auto/zh",
            name: "Sina Visitor System"
            i:999
        })
     * 可能会出现的错
     *      确保 path 是一个数组。
            确保 path 数组中的每个元素都是字符串。
            确保 obj 是一个正常对象。
     */
    setFolder:function(path, folder)
    {
        if (typeof markBooks === 'undefined') {
            throw new Error('markBooks is not defined');
        }

        // 开始时设置为最外层对象
        let obj = markBooks;  
        for (let i = 0;; i++) 
        {
            if (! (path[i] in obj)) 
            {
                // 如果中间某一步找不到，则创建路径
                obj[path[i]] = {};
            }

            if(i + 1 == path.length)
            {
                //设置 结束
                Object.assign(obj,folder);  
                return;
            }
            else
            {
                // 按照路径逐步深入
                obj = obj[path[i]];  
            }
        }
    },
    deleteAll:function()
    {
        let mbkes = Object.keys(markBooks);
        for(let i = 0; i < mbkes.length; i ++)
        {
            delete markBooks[mbkes[i]];
        }
    },
    /**
     * 创建路径
     * @param {字符组} path (例如['收藏夹', '视频'])
     * @param {对象} leftDiv jquery的元素
     *  @param {对象数组} itemDivs jquery的元素
     * 
     * return {对象}   folder:{folderDiv:[], itemDiv:[], files:[], folders:{}}
     */
    createPath:function(path, leftDiv, itemDivs)
    {
        if (typeof markBooks === 'undefined') {
            throw new Error('markBooks is not defined');
        }
        // 开始时设置为最外层对象
        let obj = markBooks;  
        for (let i = 0;; i++) 
        {
            if(!('folders' in obj))
            {
                obj['folders'] = {};
            }

            if (! (path[i] in obj.folders)) 
            {
                // 如果中间某一步找不到，创建
                let folder = markBooks.newFolder(path[i]);
                //已经保存了的的不会重复保存
                if(leftDiv != null && i == 0) folder[path[i]]['folderDiv'] = leftDiv;
                if(itemDivs!= null) folder[path[i]]['itemDiv'] = itemDivs[i];
                //防止没有空的folders
                if(!('folders' in obj))
                    obj['folders'] = {};
                //保存
                obj['folders'][path[i]] = folder[path[i]];
            }

            if(i + 1 == path.length)
            {
                // 结束
                let res = {};
                res[path[i]] = obj['folders'][path[i]];
                return res;
            }
            else
            {
                // 按照路径逐步深入
                obj = obj['folders'][path[i]];  
            }
        }
        return obj;
    },
    /**
     * 创建一个新的空folder
     * @param {字符} name 
     * @returns 
     */
    newFolder:function(name) 
    {
        let folder = {};//没有的话 创建文件夹
        let files = [];
        let folders = {};
        folder[name] = {};
        folder[name]['files'] = files;
        folder[name]['folders'] = folders;
        folder[name]['folderDiv'] = null;
        return folder;
    },
    /**
     * 获取指定文件夹下所有子文件
     * @query {字符数组} path  (例如['收藏夹', '视频'])
     * 返回值是引用，修改会影响到原数据
     */
    getAllFiles:function(path){
        //路径必须是数组 和不能为空
        if (typeof markBooks === 'undefined' ||!Array.isArray(path)) {
            return null;
        }
        //获取folder
        let folderName = path[path.length - 1];
        let object = this.getFolder(path);
        if(object == null) return [];
        //获取folder的值
        if(folderName in object)
            object = object[folderName];
        else 
            object = markBooks;
        if(object == null) return [];
        //获取files
        let files = [];
        if('files' in object)
        {
            for(let i in object.files)
            {
                files.push(object.files[i]);
            }
        }
        //进入子文件夹和保存结果
        for(let i in object.folders)
        {
            files = files.concat(this.getAllFiles(path.concat(i)));
        }
        return files;
    }
};

//   writable: false, //是否可以修改 默认是true
//   enumerable: false, //是否可被for in遍历 仅仅只能控制 for in 无法控制访问
const properties = [
    { key: 'forEachFile', writable: false, enumerable: false},
    { key: 'forEachFolder', writable: false, enumerable: false},
    { key: 'deleteAll', writable: false, enumerable: false},
    { key: 'createPath', writable: false, enumerable: false},
    { key: 'newFolder', writable: false, enumerable: false},
    { key: 'setFolder', writable: false, enumerable: false},
    { key: 'getFolder', writable: false, enumerable: false},
    { key: 'getAllFiles', writable: false, enumerable: false},
];

properties.forEach(property => {
    Object.defineProperty(markBooks, property.key, {
        writable: property.writable,
        enumerable: property.enumerable,
    });
});


let mbLeft = null;
let foldersDiv = null;
let backfoloderDiv = null;
let activePath = [];
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
</div>
`
let contentDiv = null;
let fileHtml = 
`
<div  class="
file-row 
click-pointer 
flex-md-row 
align-items-center "  title='{{url}}' >
    <div class="item-left">
        <img class='file-ico' src='{{icon}}' onerror="src='/static/icons/defIcon.png'"
        >
    </div>

    <div class="item-right">
        <p class="file-name one-line">{{name}}</p>
    </div>

    <div class="check-div" style="display:none">
        <input class="checkbox" type="checkbox" >
    </div>
</div>
`
let contentFolderHtml=
`
<div  class="
file-row 
click-pointer 
flex-md-row 
align-items-center ">
    <div class="item-left">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">
            <path d="M.54 3.87.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31zM2.19 4a1 1 0 0 0-.996 1.09l.637 7a1 1 0 0 0 .995.91h10.348a1 1 0 0 0 .995-.91l.637-7A1 1 0 0 0 13.81 4zm4.69-1.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139q.323-.119.684-.12h5.396z"/>
        </svg>
    </div>

    <div class="item-right">
        <p class="file-name one-line">{{name}}</p>
    </div>

    <div class="check-div" style="display:none">
        <input class="checkbox" type="checkbox" >
    </div>
</div>
`
let backFoloderHtml = 
`
<div  class="
file-row 
click-pointer 
flex-md-row 
align-items-center " tag='back'>
    <div class="item-left">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/> 
        </svg>
    </div>

    <div class="item-right">
        <p class="file-name one-line">返回</p>
    </div>
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
let pathInput = null;
let folderSelectDiv = null;
let folderSelectHtml = 
`
<a class="dropdown-item" >{{folderName}}</a>
`
let addFileBtn = null;

//管理页
let editFolderPage = null;
let editFilePage = null;
let editFileData = null;
let editFolderPath = "";
//保存选定文件 {i, name, url...}
let checkFiles = [];
//保存选定文件夹字符路径 ['[name1, name2, name3..]', '[name1, name2, name3..]']
let checkFolders = [];
let copyBtn = null;
let copyPath = [];
let pasteBtn = null;
let copyfiles = [];
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
        let editBtns = $('#editBtns')
        var style = editBtns.attr('style') || '';
        if(style == '')
        {
            editBtns.attr('style', 'display:none !important;');
        }
        else
        {
            // 使用正则表达式查找并替换 display 属性值，同时添加 !important 标志
            style = style.replace(/display:\s*[^;]+;/gi, 'display: block !important;'); 
            editBtns.attr('style', style)
        }
        //重置勾选
        setAllCheck(true)
        //隐藏编辑按钮


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
    verifBtn.attr('disabled', true);
    setTimeout(() => {
        verifBtn.attr('disabled', false);
    }, 30000);

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
                showInfo("发送验证码成功", "验证码已发送到邮箱，请注意查收");
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

    userPage = $('#userPage');

    editFolderPage = $("#editFolderPage");
    editFilePage = $("#editFilePage");
    copyBtn = $('#copyBtn');
    pasteBtn = $('#pasteBtn');

    userMailP = $("#userMailP");

    dialogDiv = $("#dialogDiv");
    dailogTitleH = $("#dailogTitleH");
    dialogMsgP= $("#dialogMsgP");
    dialogCloseBtn = $("#dialogCloseBtn");

    mbLeft = $("#mb-left");
    foldersDiv = $('#foldersDiv');
    contentDiv = $('#contentDiv');

    addUrlInput = $("#addUrlInput");
    addNameInput = $("#addNameInput");
    pathInput = $("#folderNameInput");
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

    window.delFile = delFiles; 
    //添加页下拉
    $('#dropdown').click(dropdown);

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
        clearCookie('mail');
        clearCookie("token");
        window.localStorage.removeItem("mark_books");

        location.reload();
    })

    //打开添加页书签
    $('#addBtn').click(function()
    {
        setSelect();
        switchPage($('#addPage'));
    })

    //管理编辑页
    $('#editBtn').click(function()
    {
        homeState = 'edit';

        switchPage(homePage);
        $('#homeHeadDiv').hide();
        $('#searchDiv').hide();
        $('#homeBackBtn').show();
        $('#editBtns').show()

        setFolderMargin()
        setAllCheck(false)
    })

    copyBtn.click(copyClick);
    pasteBtn.click(pasteClick);
    $('#deleteBtn').click(deleteClick);
    $('#editObjectBtn').click(editObjectClick);
    $('#selectAll').click(selectAllClick);


    //修改文件夹名字
    editFolderPage.find("button").first().click(function(){
        
        editFolder(editFolderPath, editFolderPage.find('input').prop('value'));
    });

    //修改文件名字
    editFilePage.find("button").first().click(function(){
        editFile();
    });

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

    //获取所有files
    let files = markBooks.getAllFiles([]);

    let s =  JSON.stringify(files, 
        function(k, v){
        if(
            k == 'folderDiv' | 
            k == 'fileDiv' |
            k == 'itemDiv' |
            k == 'ico' |
            k == 'create_time' |
            k == 'user')
                return undefined
        else
                return v;
    });

    window.localStorage.mark_books = encodeURIComponent(s);

}

/**
 * 勾选div点击事件
 */
function checkClick(event)
{
    let check = $(this);
    if(check.prop('checked'))
    {
        //勾选
        if(event['data']['type'] == 'folder')
            checkFolders.push(event['data']['data'])
        else
        {
            checkFiles.push(event['data']['data'])
        }
    }
    else
    {
        //取消勾选
        if(event['data']['type'] == 'folder')
            checkFolders.splice(checkFolders.indexOf(event['data']['data']), 1)
        else
            checkFiles.splice(checkFiles.indexOf(event['data']['data']), 1)
    }

    event.stopPropagation()
}

/**
 * 设置勾选
 * @param {是否隐藏 } hide [false | true]
 * @param {是否勾选 } checked [false | true]
 */
function setAllCheck(hide = false, checked = false)
{
    let folder = markBooks.getFolder(activePath);
    if(!folder) return;
    let activeFolder = activePath[activePath.length - 1];
    if(!folder[activeFolder]) return;
    //处理文件夹勾选
    Object.keys(folder[activeFolder]['folders']).forEach((key)=>
    {
        let folderDiv = folder[activeFolder]['folders'][key]['itemDiv'];
        if(!folderDiv) return;
        let checkDiv = folderDiv.children().last();
        //处理勾选

        if(hide)
            checkDiv.hide();
        else
            checkDiv.show();
        checkDiv.children().first().prop('checked', checked)
    })

    //处理文件勾选
    folder[activeFolder]['files'].forEach((file)=>
    {
        let fileDiv = file['fileDiv'];
        if(!fileDiv) return;
        let checkDiv = fileDiv.children().last();
        if(hide)
            checkDiv.hide();
        else
            checkDiv.show();
        checkDiv.children().first().prop('checked', checked)
    })
    //重置变量
    checkFiles = [];
    checkFolders = [];
}

/**
 * 复制点击事件
 */
function copyClick(event)
{
    if(checkFiles.length == 0 && checkFolders.length == 0)
    {
        showInfo("复制", "请先勾选文件或文件夹");
        return;
    }
    pasteBtn.toggle();
    copyBtn.toggle();

    //保存路径
    copyPath = activePath.slice();

    //保存复制的file
    let files = [];
    //拷贝一下防止影响
    files = JSON.parse(JSON.stringify( files.concat(checkFiles), function(k, v){
        if(k == 'fileDiv' | k == 'itemDiv')
            return undefined;
        else
            return v;
    }));
    //去掉folder路径
    for(let i in files)
    {
        files[i]['folder'] = [];
    }


    //保存复制的folder内的file
    for(let i in checkFolders)
    {
        let strpath = checkFolders[i];
        let arrpath = JSON.parse(strpath);
        //深拷贝一下防止影响
        let allfiles = JSON.parse(JSON.stringify( markBooks.getAllFiles(arrpath), function(k, v){
            if(k == 'fileDiv' | k == 'itemDiv')
                return undefined;
            else
                return v;
        }));
        //去除文件夹下文件路径
        for(let j in allfiles)
        {
            let filePath = JSON.parse(allfiles[j]['folder']);
            allfiles[j]['folder'] = filePath.slice(copyPath.length);
        }
        files = files.concat(allfiles);
    }
    copyfiles = files;
}

/**
 * 粘贴事件
 */
function pasteClick(event)
{
    pasteBtn.toggle();
    copyBtn.toggle();

    if(copyfiles.length == 0)
        return;

    //加上当前路径
    for(let i in copyfiles)
    {   
        let file = copyfiles[i];
        file['folder'] =  activePath.concat(file['folder']);
    }

    addFilesNet(copyfiles, function(seccess, files)
    {
        if(seccess)
        {
            showInfo("粘贴成功", "粘贴成功");
            //更新ui
            importFiles(files);
            saveMarkBook();
        }
        else
        {
            showInfo("粘贴失败", "粘贴失败");
        }
    });

    //清空勾选
    setAllCheck(false);
    copyfiles = [];
    copyPath = [];
}


/**
 * 删除点击事件
 */
function deleteClick(event)
{   
    if(checkFiles.length == 0 && checkFolders.length == 0)
    {
        showInfo("删除", "请先勾选文件或文件夹");
        return;
    }


    for(let i in checkFolders)
    {
        //添加要删除文件夹内的文件
        let arrPath = JSON.parse(checkFolders[i]);
        let allfiles = markBooks.getAllFiles(arrPath);
        checkFiles = checkFiles.concat(allfiles);
    }

    showInfo("删除中", "删除中，请稍候");
    //删除文件和文件ui
    delFiles(checkFiles, function(seccess)
    {
        if(seccess)
        {
            //删除文件夹和ui
            for(let i in checkFolders)
            {
                //删除勾选的文件夹ui
                let arrPath = JSON.parse(checkFolders[i]);
                let folder = markBooks.getFolder(arrPath);
                let folderName = arrPath[arrPath.length - 1];
                let itemDiv = folder[folderName]['itemDiv'];
                let folderDiv = folder[folderName]['folderDiv'];//快捷文件夹不过这里应该删不了
                if(!!folderDiv) folderDiv.remove();
                itemDiv.remove();
                delete folder[folderName];
            }

            //如果父文件夹没有文件和文件夹了，删除父快捷文件夹
            var folder = markBooks.getFolder(activePath);
            let folderName = activePath[activePath.length - 1];
            //是否还有文件和文件夹
            let parentFiles = folder[folderName]['files'];
            let childrenFolders = folder[folderName]['folders'];
            if(parentFiles.length == 0 && Object.keys(childrenFolders).length == 0)
            {
                //删除左边快捷文件夹
                let folderDiv = folder[folderName]['folderDiv'];
                if(!!folderDiv) {
                    folderDiv.remove();
                    //删除数据
                    delete folder[folderName];
                    //如果当前文件夹是激活文件夹，清空激活文件夹
                    if(folderName == activePath[activePath.length - 1])
                        activePath = [];
                    
                    setFolderMargin()
                }
            }

            checkFiles = [];    
            checkFolders = [];
            setAllCheck(false);
        }
        else
        {
            showInfo("删除失败", "可能是选择文件或文件夹内文件过多，请分批删除");
        }
    });


}

function editObjectClick(event)
{
    //如果选中文件+选中文件夹大于1，则不允许编辑
    if(checkFiles.length + checkFolders.length > 1)
    {
        showInfo("编辑", "请选择单个文件或文件夹");
        return;
    }
    if(checkFiles.length == 1)
    {
        //编辑文件
        let file = checkFiles[0];
        editFileData = file;
        switchPage(editFilePage);
        $("#fileNameInput").val(file['name']);
        $("#fileUrlInput").val(file['url']);
    }
    else if(checkFolders.length == 1)
    {
        //编辑文件夹
        let objStrPath = checkFolders[0];
        let arrPath = JSON.parse(objStrPath);
        switchPage(editFolderPage);
        editFolderPath = arrPath;
        $("#folderName ").val(arrPath[arrPath.length - 1]);
    }

    setAllCheck(false,false)
    checkFiles == [];
    checkFolders == [];

}

/**
 * 全选点击事件
 */
function selectAllClick(event)
{
    let checks = [];
    //保存已经点击的数量
    let clickCount = 0;
    //获取当前文件夹内所有文件的check
    let folder = markBooks.getFolder(activePath);
    if(!folder) return;
    let folderName = activePath[activePath.length - 1];
    if(!folderName) return;
    let files = folder[folderName]['files'];
    for(let i in files)
    {
        let file = files[i];
        let check = file['fileDiv'].find('input');
        //计数
        if(!!check.prop('checked'))
        {
            clickCount++;
        }
        //保存
        checks.push(check);
    }
    //获取当前文件夹内所有文件夹的check
    let folders = folder[folderName]['folders'];
    for(let i in folders)
    {
        let folder = folders[i];
        let check = folder['itemDiv'].find('input');
        //计数
        if(!!check.prop('checked'))
        {
            clickCount++;
        }
        //保存
        checks.push(check);
    }
    //触发点击事件
    for(let i in checks)
    {
        if(clickCount == checks.length)
        {
            checks[i].click();
            continue
        }
        if(!checks[i].prop('checked'))
            checks[i].click();
    }
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



    //转义
    let ecodefileName = encodeURIComponent(fileName);
    let ecodefileUrl = encodeURIComponent(fileUrl);
    //云修改
    request(
        `mail=${userMail}&token=${userToken}&id=${editFileData['i']}&name=${ecodefileName}&url=${ecodefileUrl}`,
        url + '/index.php/index/index/editFile',
        (res)=>{
            saveResInfo(res);
            let resMap = JSON.parse(res.currentTarget.response); 
            if('code' in resMap & resMap['code'] == '200')
            {
                showInfo("修改成功", "修改成功");
                //本地修改
                let file = editFileData;
                file['name'] = fileName;
                file['url'] = fileUrl;
                //更新ui
                let fileDiv = file['fileDiv'];
                fileDiv.attr('title',bencode( file['url']));
                let nameDiv = fileDiv.find('.file-name.one-line');
                nameDiv.text(file['name']);

                //保存
                saveMarkBook();
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
 * @param {原文件夹路径} fromPath ['1', '2', '旧的名字'..]
 * @param {新文件夹名字} toName  "新文件夹名字"
 */
function editFolder(fromPath, toName)
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
    //云修改
    let strFormPath = encodeURIComponent(JSON.stringify(fromPath));
    fromPath.pop();
    fromPath.push(toName);
    let strTopPath = encodeURIComponent(JSON.stringify(fromPath));
    request(
        `mail=${userMail}&token=${userToken}&fromPath=${strFormPath}&toPath=${strTopPath}`,
        url + '/index.php/index/index/editFolder',
        (res)=>{
            saveResInfo(res);
            let resMap = JSON.parse(res.currentTarget.response); 
            if('code' in resMap & resMap['code'] == '200')
            {
                
                sync(()=>{editFolderPath = toName;});
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
 * @param {文件} files 
 * @param {回调函数} back (seccess[true|false])
 */
function delFiles(files, back= null)
{
    let ids = [];
    for(let i in files)
    {
        ids.push(files[i]['i']);
    }
    let idsStr = encodeURIComponent( JSON.stringify(ids));
    request(
        `token=${userToken}&is=${idsStr}`,
        url + "/index.php/index/index/delMarkBooks",
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if('code' in mapRes & mapRes['code'] == 200)
            {
                for(let i in files)
                {
                    //删除勾选的file文件夹内所有子file ui
                    let file = files[i];
                    let fileDiv = file['fileDiv'];  
                    if(!!fileDiv)//未显示的file是不需要删除的
                        fileDiv.remove();
                    //字符路径转数组路径
                    let arrPath = JSON.parse(file['folder']);
                    //删除数据
                    let folder = markBooks.getFolder(arrPath);
                    let folderName = arrPath[arrPath.length - 1];
                    let nowFiles = folder[folderName]['files'];
                    nowFiles.splice(nowFiles.indexOf(file), 1);
                }
                if(!!back) back(true);
                saveMarkBook();
                showInfo("删除成功", "删除书签成功");
                return
            }
            if(!!back)back(false)
            },
            'post'
        );
    return false;
}


/**
 * 添加文件夹视图
 * 返回创建的文件夹div
 */
function addFolderView(parentFolderPath, folderName)
{
    let itemFolderHtml = contentFolderHtml.replace(/\{\{name\}\}/g, folderName);
    let itemFolder = $(itemFolderHtml);

    //计算插入位置
    let folder = markBooks.getFolder(parentFolderPath);
    if(folder !== null && parentFolderPath.length > 0)
    {
        let files = folder[parentFolderPath[parentFolderPath.length - 1]]['files'];
        if(files.length > 0)
        {
            let firstFile = files[0];
            let fileDiv = firstFile['fileDiv'];
            if(!!fileDiv)
            {
                fileDiv.before(itemFolder);
                return itemFolder;
            }
        }
    }
    contentDiv.append(itemFolder);
    return itemFolder;
}

/**
 * 
 * @param {路径数组} path [..., '父文件夹', '文件夹']
 * @returns  folder:{name, files, folders, folderDiv, itemDiv}
 */
function addFolderLocal(path = ['其他'])
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

    let name = null;

    let itemDivs = [];
    let localPath = [];
    let folderDiv = null;
    for(let i = 0; i < path.length; i ++)
    {
        name = path[i];
        let itemFolderDiv = null;
        let beforePath = JSON.parse(JSON.stringify(localPath));
        localPath.push(name);
        //判断当前路径是否已经存在
        if(markBooks.getFolder(localPath) == null) 
        {
            //插入左侧快捷文件夹ui
            if(i == 0)
            {
                foldersDiv.append(folderHtml.replace('{{name}}', name));
                folderDiv = foldersDiv.children().last();
                //添加点击事件
                folderDiv.on('click', {'path':  JSON.stringify(localPath)}, folderClick);
                //folderDiv.children().last().click(delFolder);
            }
            //插入右侧文件夹的ui
            itemFolderDiv = addFolderView(beforePath, name);
            //添加点击事件
            itemFolderDiv.on('click', {'path':  JSON.stringify(localPath)}, folderClick);
            let checkDiv = itemFolderDiv.children().last();
            if(homeState == 'edit')
                checkDiv.show();
            let check = itemFolderDiv.find("input");
            check.on('click', {'data':  JSON.stringify(localPath), type: 'folder'}, checkClick);//只能后面再转arr
            //添加到markBooks
            //是否隐藏
            let strActivePath = JSON.stringify(activePath);
            if(strActivePath == '[]' | JSON.stringify(beforePath) != strActivePath)
            {
                itemFolderDiv.hide();
            }
        }
        itemDivs.push(itemFolderDiv);
    }

    //创建路径和保存div
    let  folder = markBooks.createPath(path, folderDiv, itemDivs);
    setFolderMargin();
    return folder;
}

/**
 * 打开和关闭文件夹 ui处理
 * 
 * @param {字符数组} path [..., '父文件夹', '文件夹']
 */
function toggleFolder(path)
{
    //处理文件
    let index = null;
    let folderName = path[path.length - 1]
    let folder = markBooks.getFolder(path);

    //如果folderName不存在folder 这个文件夹可能已经删除
    if(!(folderName in folder))return;
    let files = folder[folderName]['files'];
    if(!!files)
    {
        for(index in files)
        {
            let file = files[index];
            if(!!file['fileDiv'])
            {
                //存在元素就删除
                file['fileDiv'].remove();
                file['fileDiv'] = null;
            }
            else
            {
                //不存在就创建
                addFileView(file)
            }
        }
    }

    //处理返回上个文件夹按钮
    if(!!backfoloderDiv)
    {
        backfoloderDiv.remove();
        backfoloderDiv = null;
    }
    if(path.length > 1)
    {
        contentDiv.append(backFoloderHtml);
        backfoloderDiv = contentDiv.children().last();
        backfoloderDiv.click(function(){
            //获取上个文件夹div
            let lastFolder = markBooks.getFolder(path.slice(0, -1));
            let lastFolderDiv = lastFolder[path[path.length - 2]]['itemDiv'];
            lastFolderDiv.click();
        });
    }

    //处理文件夹内的文件夹
    let folders = folder[folderName]['folders'];
    for(let subFolderName in folders)
    {
        let subFolder = folders[subFolderName];
        let div = subFolder.itemDiv;
        div.toggle()
    }



    //处理左侧快捷文件夹
    let objDiv = folder[folderName]['folderDiv'];
    if(!!objDiv)
    {
        objDiv[0].style.backgroundColor == ""
        ?
        objDiv[0].style.backgroundColor = "#fff"
        :
        objDiv[0].style.backgroundColor = ""

        let arrow = objDiv[0].firstElementChild.nextElementSibling;
        arrow.style.display=='none'?arrow.style.display = 'block' : arrow.style.display = 'none';
    }

}

/**
 * 文件夹点击事件
 * 
 */
function folderClick(obj)
{
    let sClicikFolderPath = obj.data['path'];
    let clickFolderPath = JSON.parse(sClicikFolderPath);
    let path = JSON.parse(obj['data']['path']);//现在点击的文件夹
    let strActivePath = JSON.stringify(activePath);//现在已经点开的文件夹
    if(strActivePath!= sClicikFolderPath)
    {
        //不是点击同一个文件夹
        if(activePath.length != 0)
        {
            toggleFolder(activePath);//关掉已经打开的
            if(homeState == 'edit') 
            {
                setAllCheck(true);//关掉勾选
                checkFiles = [];
                checkFolders = [];
            }

        }
        toggleFolder(clickFolderPath);//打开当前点击的
        activePath = path;

        //设置勾选
        if(homeState == 'edit') setAllCheck();
    }
    else
    {
        //点击同一个文件夹
        if(homeState == 'edit')
        {
            let folderName = clickFolderPath[clickFolderPath.length - 1];
            editFolderPage.find("input").prop('value', folderName);
            editFolderPath = clickFolderPath;
            switchPage(editFolderPage);
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
        let clickFileName = obj.data['file']['name'];
        let url = obj.data['file']['url'];
        editFileData = obj['data']['file'];

        $("#fileNameInput").prop('value', clickFileName);
        $("#fileUrlInput").prop('value', url);
        switchPage(editFilePage);
    }
    else
    {
        let url = bdecode(obj.currentTarget.getAttribute('title'));
        window.open (url,'newwindow' + url)
    }
}

/**
 * 添加书签
 */
function addFile()
{
    let addUrl = addUrlInput.val().trim();
    let name = addNameInput.val().trim();
    let path = pathInput.val().trim();
    if(!addUrl || !name || !path)
    {
        showInfo("填写错误", "网址 名字 收藏文件 不能为空");
        return;
    }

    if(name.length > 100)
        name = name.substring(0, 100);

    if(path.length > 100)
        path  = path.substring(0, 900);

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

    //字符路径转数组字符
    let arrpath = path.split(">");

    addFilesNet([{name:name, url:addUrl, folder:arrpath}], function(success, files)
        {
            if(success){
                importFiles(files)
                clickFirst();
                showInfo('添加成功', "添加成功!");
            }
            else
            {
                showInfo('添加失败', "添加失败!");
            }
        });
}


/**
 * 添加书签到服务器
 * @param {数组型files} files [{name, url, arrpath },...]
 * @param {funct(success(true or false), files([{name, url, folder, i...},...])){}} back 同步完成返回
 */
function addFilesNet(files, back)
{
    let strFiles =  JSON.stringify(files, function(key, value) {
        if ( key === 'fileDiv' && value !== null) {
            return undefined;
        }
        return value;
    });

    //转义
    strFiles = encodeURIComponent(strFiles);

    if(!strFiles) return;

    request(
        `token=${userToken}&files=${strFiles}`,
        url + "/index.php/index/index/addMarkBooks",
        function(res){
            saveResInfo(res);
            let mapRes = JSON.parse(res.currentTarget.response);
            if('code' in mapRes & 'data' in mapRes & mapRes['code'] == '200')
            {
                let files = mapRes['data'];
                return back(true, files);
            }
            return back(false);
        },
        'post')
}


/**
 * 
 * @param {字符数组} path [..., '父文件夹', '文件夹']
 * @param {对象} file  {name, url, folder, i}
 */
function addFileLocal(path, file)
{

    //本地有没有这个文件夹
    let folder = markBooks.getFolder(path);
    let folderName =  path[path.length - 1];
    if(folder == null)
        folder = addFolderLocal(path);//创建
        ///////////////////////////////////////////////////////////////////////////
    //当前是否打开这个文件夹
    if(JSON.stringify(path) ==  JSON.stringify(activePath))
    {
        addFileView(file);
    }
    //保存到markbooks
    folder[folderName]['files'].push(file);
    setFolderMargin();
}

/**
 * 添加文件视图
 *  * @param {对象} file  {name, url, folder, i}
 */
function addFileView(file)
{

    let url = file['url'];
    let host = url.replace( url.replace(/(http|https):\/\/(www.)?(\w+(\.)?)+\/?/,""),"");
    //拼接html
    let thisFileHtml = fileHtml.replace('{{icon}}', host + '/favicon.ico');
    thisFileHtml =thisFileHtml.replace(/{{name}}/g, file['name']);
    thisFileHtml = thisFileHtml.replace("{{i}}", file['i']);
    
    thisFileHtml = thisFileHtml.replace('{{url}}', bencode(file['url']));
    //contentdiv里面最后一个元素是不是backhtml
    let lastObj = contentDiv.children().last();
    let fileDiv = $(thisFileHtml);
    if(lastObj.attr('tag') == 'back')
    {
        lastObj.before(fileDiv)
    }
    else
    {
        contentDiv.append(fileDiv);
    }
    //添加事件
    fileDiv.on('click', {file:file}, fileClick);
    let checkDiv = fileDiv.children().last();
    if(homeState == 'edit')
        checkDiv.show();
    let check = fileDiv.find("input");
    check.on('click', {'data':  file , type: 'file'}, checkClick);
    //保存文件元素
    file['fileDiv'] = fileDiv;
}

/**
 * 解析
 */
function parseMarkBooks(importMB){
    let record = false;
    let folders = [];  // [祖，父，儿.......]

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
                if(!folders[folders.length - 1])
                {
                    folders.push('其他');
                }
                if( text.trim() == "")
                     text = '未命名'; //如果是空的就命名为空
                let path = [];
                Object.assign(path,folders);//深拷贝路径
                files.push({
                    'name':text, 
                    url:href, 
                    folder:path
                })//创建一个文件夹
                tagName = "";
            } 
            record = false;
        },
        onclosetag(tagName){
            if(tagName == 'dl'){
                folders.pop();
                }
        }
        
    });
    parser.write(importMB);
    parser.end();
    //return ([]);
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
    var files = [];
    files = markBooks.getAllFiles([]);
    //根据i排序
    files.sort(function(a, b){
        return a['i'] - b['i'];
    });
    feature = JSON.stringify(files, 
        function(k, v){
            if(
                k == 'folderDiv' | 
                k == 'fileDiv' |
                k == 'itemDiv' |
                k == 'ico' |
                k == 'create_time' |
                k == 'user'|
                k =='update_time')
                    return undefined
            else
                    return v;
        });

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
                contentDiv.html("");
                activePath = [];
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
        let utfText = binaryToUtf8(window.atob(text));
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

        showInfo('正在导入', "正在导入书签需要一会", false);
        addFilesNet(files, function(success, files)
        {
            if(success)
            {
                importFiles(files)
                clickFirst();
                showInfo('导入成功', "成功导入!");
            }
            else
            {
                showInfo('导入失败', "导入失败!");
            }
        });
    }
}

/**
 * 导入书签
 * @param {数组型files} inputMB [{name, url, folder:"父>子>孙>...", i...},...]
 */
function importFiles(inputMB){
    for(let i = 0 ; i < inputMB.length; i ++)
    {
        //导入的书签的路径
        let path =JSON.parse(inputMB[i]['folder'])
        addFileLocal(path, inputMB[i]);
    }
    //this.$refs.markBook2.updateFilesHeight();
}

/**
 * 导出html的主体
 */
function outputBody(folder = null, name = null)
{
    let folderName = "";
    let files = [];
    let subFolders = {};
    if(folder == null)
    {
        folder = markBooks;
        folderName = "root";
        subFolders = markBooks['folders'];
    }
    else
    {
        folderName = name;
        files = folder['files'];
        subFolders = folder['folders'];
    }
    //添加文件夹
    let strFolder = mBsFolderHtml.replace("{{folderName}}", folderName);
    //添加文件

    for(let index in files)
    {
        let strFile = mBsfileHtml.replace('{{url}}', bencode(files[index]['url']));
        strFile = strFile.replace('{{name}}', files[index]['name']);
        strFolder = strFolder + strFile;
    }
    //添加子文件夹
    for(let subFolderName in subFolders)
    {
        strFolder = strFolder + outputBody(subFolders[subFolderName], subFolderName);
    }
    //添加文件夹结尾
    strFolder = strFolder + `</DL><p>`;
    return strFolder;
}

/**
 * 导出书签
 */
function outputHtmlMB()
{
    let strFolder = outputBody();

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
    if(!('folders' in markBooks))return;

    let folders = markBooks.folders;
    let keys = Object.keys(folders);
    if(keys.length > 0)
    {
        let folderDiv = folders[keys[0]]['folderDiv'];
        folderDiv.trigger('click');
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
 * 添加书签页下拉框下拉事件
 */
function dropdown(data) 
{
    let m = data.currentTarget.nextSibling.nextSibling;  
    if(m.style.display == 'none'||!m.style.display)
    {
        m.style.display = 'block'
        pathInput.val('')
        setSelect();
    }
    else
    {
        m.style.display = 'none'
    }
}

/**
 * 
 * 添加书签页下拉框里面元素点击事件
 * @param {字符串} folderName {原本的文件夹名字}
 */
function selectClick(folderName)
{ 
    //修改输入框
    let pathValue = pathInput.val().trim();
    if(pathValue != '')
        pathValue = pathInput.val() + '>' + folderName;
    else
        pathValue = folderName
    pathInput.val(pathValue)
    //修改下拉框
    let folder = markBooks.getFolder(pathValue.split(">"))[folderName]
    let folders = folder['folders'];
    if(Object.keys(folders).lenght != 0)
        setSelect(folders)
}

/**
 * 设置下拉
 *  @param {对象} parentFolder {'视频网站','音乐网站'...}
 */
function setSelect(parentFolder = null) 
{
    folderSelectDiv.html("")

    if(parentFolder == null) parentFolder =  markBooks['folders'] 
    if(!parentFolder) return;
    Object.keys(parentFolder).forEach((key) => {
            folderSelectDiv.append(folderSelectHtml.replace('{{folderName}}', key));
            folderSelectDiv.children().last().click(()=>{
                selectClick(key);
                
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
        var files = JSON.parse( decodeURIComponent(strMB));
        importFiles(files)
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

        let shortInfo = encodeURIComponent( errInfo.slice(0, 4000));
        request(`mail=${userMail}&info=${shortInfo}`,"/index.php/index/index/jsError", function(data){
        }, 'post')
}





/**
 * bug
 */