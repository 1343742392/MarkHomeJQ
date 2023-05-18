<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"D:\MyFile\Code\Website\MarkHomeJQ\public/../application/index\view\index\index.html";i:1676637395;s:77:"D:\MyFile\Code\Website\MarkHomeJQ\application\index\view\common\markbook.html";i:1675327313;s:73:"D:\MyFile\Code\Website\MarkHomeJQ\application\index\view\common\back.html";i:1675233900;}*/ ?>
<!DOCTYPE html>
<html lang="zh" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='icon' href="favicon.ico">    
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <!-- <script src="/static/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="/static/css/app.css">
    <script src="/static/js/jquery.js"></script>
    <script src="/static/js/md5.js"></script>
    <!-- <script type="module" src="/static/js/request"></script> -->
    <script type="text/javascript" src="/static/js/htmlparser.js"></script>

    <script type="module" src="/static/js/main.js"></script>
    <script  type="text/javascript">
  
    </script>
    <title>书签主页</title>
</head>
<body>
    <i class="bi bi-0-circle"></i>
    <div id="app">
        <div class="main">
            <div class="modal" id="dialogDiv" tabindex="-1" style="display: none;background-color: #00000040;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dailogTitleH">title</h5>
                        </div>
    
                        <div class="modal-body">
                            <p id="dialogMsgP">Modal body text goes here.</p>
                        </div>
    
                        <div class="modal-footer">
                            <button type="button" id="dialogCloseBtn" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="loginPage" class="h-100 center-container"  style="display: none;">
                <div class="radius-card w-100 p-4">
                    <h1 class="font-weight-bold m-2">书签主页</h1>
                    <p class="font-bold text-muted small mb-4">摆脱设备浏览器限制 网页式书签</p>
                
                    <div class="input-group mb-3">
                        <input type="text" id="mailInput" class="form-control" placeholder="邮箱" >
                        
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" id="verifBtn" type="button">发送验证码</button>
                        </div>
                    </div>

                    <input type="text" id="verifCodeInput"  class="form-control mb-3" placeholder="邮箱接收到的验证码">

                    <button id="loginOrRegBtn" class="btn btn-primary btn-block">注册或者登录</button>
                </div>
            </div>

            <div id="homePage" style="display: block;" >
                <div  id="homeHeadDiv" >
                    <div  class="home-head radius-card d-flex justify-content-between">
                        <svg id="userSvg"  class="click-pointer icon"  t="1643803198497" style="margin: 5px;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4479" width="40" height="40"><path d="M512 1022.1056c-282.3168 0-511.232-228.864-511.232-511.0784S229.632 0 512 0c282.3168 0 511.232 228.8128 511.232 511.0272 0 282.2656-228.864 511.0784-511.232 511.0784z m0-926.2592a415.2832 415.2832 0 0 0-415.3856 415.232c0 107.2128 41.0624 204.6464 107.8272 278.3232 60.16-29.1328 38.0416-4.9152 116.736-37.2736 80.5888-33.1264 99.6352-44.6464 99.6352-44.6464l0.768-76.288s-30.1568-22.8864-39.5264-94.72c-18.8928 5.4272-25.088-22.016-26.2144-39.424-1.024-16.896-10.9568-69.4784 12.0832-64.7168-4.7104-35.1744-8.0896-66.8672-6.4-83.6608 5.7344-58.88 62.976-120.5248 151.0912-124.9792 103.68 4.4544 144.7424 66.048 150.528 124.928 1.6384 16.8448-2.048 48.5376-6.7584 83.6096 23.04-4.6592 13.0048 47.872 11.8784 64.7168-1.024 17.408-7.3728 44.7488-26.2144 39.3728-9.4208 71.7824-39.5776 94.464-39.5776 94.464l0.7168 75.9296s19.0976 10.8032 99.6352 43.9296c78.6944 32.3584 56.576 9.5744 116.736 38.7584a413.184 413.184 0 0 0 107.776-278.3744A415.232 415.232 0 0 0 512 95.7952z" fill="#8a8a8a" p-id="4480"></path></svg>
    
                        <svg id="setSvg" class="click-pointer icon" t="1674907312248"  viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2701" width="48" height="48"><path d="M512 328c-100.8 0-184 83.2-184 184S411.2 696 512 696 696 612.8 696 512 612.8 328 512 328z m0 320c-75.2 0-136-60.8-136-136s60.8-136 136-136 136 60.8 136 136-60.8 136-136 136z" p-id="2702" fill="#8a8a8a" data-spm-anchor-id="a313x.7781069.0.i4" class="selected"></path><path d="M857.6 572.8c-20.8-12.8-33.6-35.2-33.6-60.8s12.8-46.4 33.6-60.8c14.4-9.6 20.8-27.2 16-44.8-8-27.2-19.2-52.8-32-76.8-8-14.4-25.6-24-43.2-19.2-24 4.8-48-1.6-65.6-19.2-17.6-17.6-24-41.6-19.2-65.6 3.2-16-4.8-33.6-19.2-43.2-24-14.4-51.2-24-76.8-32-16-4.8-35.2 1.6-44.8 16-12.8 20.8-35.2 33.6-60.8 33.6s-46.4-12.8-60.8-33.6c-9.6-14.4-27.2-20.8-44.8-16-27.2 8-52.8 19.2-76.8 32-14.4 8-24 25.6-19.2 43.2 4.8 24-1.6 49.6-19.2 65.6-17.6 17.6-41.6 24-65.6 19.2-16-3.2-33.6 4.8-43.2 19.2-14.4 24-24 51.2-32 76.8-4.8 16 1.6 35.2 16 44.8 20.8 12.8 33.6 35.2 33.6 60.8s-12.8 46.4-33.6 60.8c-14.4 9.6-20.8 27.2-16 44.8 8 27.2 19.2 52.8 32 76.8 8 14.4 25.6 22.4 43.2 19.2 24-4.8 49.6 1.6 65.6 19.2 17.6 17.6 24 41.6 19.2 65.6-3.2 16 4.8 33.6 19.2 43.2 24 14.4 51.2 24 76.8 32 16 4.8 35.2-1.6 44.8-16 12.8-20.8 35.2-33.6 60.8-33.6s46.4 12.8 60.8 33.6c8 11.2 20.8 17.6 33.6 17.6 3.2 0 8 0 11.2-1.6 27.2-8 52.8-19.2 76.8-32 14.4-8 24-25.6 19.2-43.2-4.8-24 1.6-49.6 19.2-65.6 17.6-17.6 41.6-24 65.6-19.2 16 3.2 33.6-4.8 43.2-19.2 14.4-24 24-51.2 32-76.8 4.8-17.6-1.6-35.2-16-44.8z m-56 92.8c-38.4-6.4-76.8 6.4-104 33.6-27.2 27.2-40 65.6-33.6 104-17.6 9.6-36.8 17.6-56 24-22.4-30.4-57.6-49.6-97.6-49.6-38.4 0-73.6 17.6-97.6 49.6-19.2-6.4-38.4-14.4-56-24 6.4-38.4-6.4-76.8-33.6-104-27.2-27.2-65.6-40-104-33.6-9.6-17.6-17.6-36.8-24-56 30.4-22.4 49.6-57.6 49.6-97.6 0-38.4-17.6-73.6-49.6-97.6 6.4-19.2 14.4-38.4 24-56 38.4 6.4 76.8-6.4 104-33.6 27.2-27.2 40-65.6 33.6-104 17.6-9.6 36.8-17.6 56-24 22.4 30.4 57.6 49.6 97.6 49.6 38.4 0 73.6-17.6 97.6-49.6 19.2 6.4 38.4 14.4 56 24-6.4 38.4 6.4 76.8 33.6 104 27.2 27.2 65.6 40 104 33.6 9.6 17.6 17.6 36.8 24 56-30.4 22.4-49.6 57.6-49.6 97.6 0 38.4 17.6 73.6 49.6 97.6-6.4 19.2-14.4 38.4-24 56z" p-id="2703" fill="#8a8a8a"></path></svg>
                    </div>
                </div>

                <div class="mark-book radius-card d-flex align-items-end">
    <div class="mb-left">
        <div id="foldersDiv" class=
        'd-flex 
        flex-column
        align-items-center' style="">
            
        </div>
    </div>

    <div  class="mb-right hide-rollbar" >
        <div id="filesDiv" class="files">
        </div>
    </div>
</div>

                <div id="homeBackBtn" class="radius-card  p-3 mb-5" style="display: none;">
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>

                <div id="searchDiv" class="home-bottom radius-card">
                    <div class="input-group">
                        <input id="searchWordInput" type="text" class="form-control" placeholder="搜索" aria-label="Recipient's username" aria-describedby="button-addon2">
                        
                        <div class="input-group-append">
                            <button class="search-btn btn btn-outline-secondary" type="button" id="searchBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="userPage" class="h-100 center-container" style="display: none">
                <div class="radius-card w-100 p-4">
                    <svg  t="1643803198497" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4479" width="40" height="40"><path d="M512 1022.1056c-282.3168 0-511.232-228.864-511.232-511.0784S229.632 0 512 0c282.3168 0 511.232 228.8128 511.232 511.0272 0 282.2656-228.864 511.0784-511.232 511.0784z m0-926.2592a415.2832 415.2832 0 0 0-415.3856 415.232c0 107.2128 41.0624 204.6464 107.8272 278.3232 60.16-29.1328 38.0416-4.9152 116.736-37.2736 80.5888-33.1264 99.6352-44.6464 99.6352-44.6464l0.768-76.288s-30.1568-22.8864-39.5264-94.72c-18.8928 5.4272-25.088-22.016-26.2144-39.424-1.024-16.896-10.9568-69.4784 12.0832-64.7168-4.7104-35.1744-8.0896-66.8672-6.4-83.6608 5.7344-58.88 62.976-120.5248 151.0912-124.9792 103.68 4.4544 144.7424 66.048 150.528 124.928 1.6384 16.8448-2.048 48.5376-6.7584 83.6096 23.04-4.6592 13.0048 47.872 11.8784 64.7168-1.024 17.408-7.3728 44.7488-26.2144 39.3728-9.4208 71.7824-39.5776 94.464-39.5776 94.464l0.7168 75.9296s19.0976 10.8032 99.6352 43.9296c78.6944 32.3584 56.576 9.5744 116.736 38.7584a413.184 413.184 0 0 0 107.776-278.3744A415.232 415.232 0 0 0 512 95.7952z" fill="#2aa515" p-id="4480"></path></svg>
            
                    <p id="userMailP">mail</p>
    
                    <button id="loginOutBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M7.5 1v7h1V1h-1z"/>
                            <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z"/>
                        </svg>

                        退出登录
                    </button>
    
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="setPage" class="h-100 center-container" style="display: none">
                <div class="radius-card w-100 p-3">
                    <button id="addBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>

                        添加书签
                    </button>

                    <button id="delBtn" class="btn w-100 btn-info mb-3 p-2 center-container">
                        <svg  class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                        </svg>

                        删除书签
                    </button>

                    <button id="importBtn" class="btn w-100 btn-primary mb-3 p-2 center-container" onclick="$('#favoritesInput').click()">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z"/>
                            <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        导入书签
                    </button>
                    <input id='favoritesInput' type="file" style='display:none'>


                    <button id="outputBtn" class="btn w-100 btn-info mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                            <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>

                        导出书签
                    </button>

                    <button id="engineBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>

                        搜索引擎
                    </button>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="addPage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-3 w-100'>
                    <input type="text" class="form-control mb-2" id="addUrlInput" placeholder="网址 例如:https://www.baidu.com/">

                    <input type="text" class="form-control mb-2" id="addNameInput" placeholder="名字 例如:百度">
                
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="folderNameInput" placeholder="新的文件夹名称">
                        
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"
                            onclick="let m = this.nextSibling.nextSibling; m.style.display=(m.style.display == 'none'||!m.style.display) ? 'block':'none';">
                                现有文件夹
                            </button>

                            <div class="dropdown-menu" id="folderSelectDiv">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>

                    <button class="btn w-100 btn-primary mb-2 p-2 center-container" id="addFileBtn">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>

                        添加
                    </button>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="enginePage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-4 w-100'>
                    <p style="color:#666;margin-top:0px">搜索引擎选择</p>
                
                    <div class="btn-group mb-5 w-100" >
                        <button id="baidu" type="button" class="btn btn-outline-secondary w-25">百度</button>
                        <button id="bing" type="button" class="btn btn-outline-secondary w-25">必应</button>
                        <button id="google" type="button" class="btn btn-outline-secondary w-25">谷歌</button>
                        <button id="kuake" type="button" class="btn btn-outline-secondary w-25">夸克</button>
                    </div>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

        </div>
    </div>
</body>
</html>