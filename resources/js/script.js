// ジャンル選択ページですべてがチェックされたとき全チェックボックスがチェックされるようにする
(function () {
    const allCheck = document.getElementById('all-check');
    const checks = document.getElementsByClassName('one-check');
    
    if(allCheck !== null) {
        allCheck.onclick = function() {
            if(allCheck.checked) {
                for(let i = 0; i < checks.length; i ++) {
                    checks[i].checked = true;
                    checks[i].disabled = true;
                }
            } else {
                for(let i = 0; i < checks.length; i ++) {
                    checks[i].checked = false;
                    checks[i].disabled = false;
                }
            }
        }
    }
}());

// 表示順序の切り替え
(function () {
    const sortSelect = document.getElementById('sort-select');
    if(sortSelect !== null) {
        sortSelect.onchange = function() {
            location.href = this.value;
        }
    }
}())
    
//読みたい本追加処理
$(function() {
    $('.want-button').on('click', function() {
        //読みたい本に追加する本のid
        const bookId = $(this).data('book-id');

        //読みたい本に追加しようとしている本の読みたい本ボタンすべて
        const buttons = $(`[data-book-id=${bookId}]`);

        //読みたい本に追加しようとしている本の読みたい本ボタンを押せなくする
        buttons.each(function(i,e) {
            e.disabled = true;
        })

        //ajax
        $.ajax({
            type:"POST",
            url:'/want-to-read',
            data:{"book_id":bookId},
            dataType:'json',
            //csrfトークンをヘッダーに含める
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        })
        .done(function(data) {
            //読みたい本に追加されたボタンの色を変えてテキスト変更
            buttons.each(function(i, e) {
                $(e).toggleClass('btn-warning btn-dark');
                $(e).text('読みたい本に追加済み')
            })
        })
        .fail(function() {
            window.alert('読みたい本の追加に失敗しました。');

            //追加に失敗した場合はボタンをクリック可にする
            buttons.each(function(i,e) {
                e.disabled = false;
            })
        })
    })
})

// ハートボタンを押されたときの処理
$(function() {
    $('.heart-button').on('click', function() {

        const button = this;

        button.disabled = true;

        // ハートボタンがついている推薦文のid
        const recommendationId = button.dataset.recommendationId;

        let settings = {
            type:'POST',
            data: {"recommendation_id": recommendationId},
            dataType:'json',
            //csrfトークンをヘッダーに含める
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }

        // ボタンがshooting-arrowクラスを持っているときはハート登録、そうでないとき削除
        let isShooting = true;
        if(button.classList.contains('shooting-arrow')) {
            settings.url = '/heart';
        } else {
            settings.url = '/heart/delete';
            isShooting = false;
        }

        $.ajax(settings)
        .done(function(data) {
            button.parentElement.getElementsByClassName('hearts-count')[0].textContent = data.count;
            if(isShooting) {
                button.classList.replace('shooting-arrow', 'removing-arrow');
            } else {
                button.classList.replace('removing-arrow', 'shooting-arrow');
            }
            button.disabled = false;
        })
        .fail(function() {
            window.alert('エラーが発生しました');
            button.disabled = false;
        })

    })
})