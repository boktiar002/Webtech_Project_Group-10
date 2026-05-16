// =====================
// Category Filter
// =====================
function filterCategory(btn, categoryId)
{
    var tabs = document.querySelectorAll('.tab-btn');
    for(var i = 0; i < tabs.length; i++)
    {
        tabs[i].classList.remove('active');
    }
    btn.classList.add('active');

    var url = "";
    if(categoryId)
    {
        url = APP_ROOT + "/Api/articles.php?category_id=" + categoryId;
    }
    else
    {
        url = APP_ROOT + "/Api/articles.php";
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if(this.readyState == 4 && this.status == 200)
        {
            var articles = JSON.parse(this.responseText);
            var grid = document.getElementById('articles-grid');

            if(articles.length == 0)
            {
                grid.innerHTML = '<p>No articles found.</p>';
                return;
            }

            grid.innerHTML = "";

            for(var i = 0; i < articles.length; i++)
            {
                var a = articles[i];

                var card = document.createElement('div');
                card.className = 'card';

                var img = document.createElement('img');
                img.src = a.featured_image_path ? a.featured_image_path : 'https://placehold.co/400x180';

                var cardBody = document.createElement('div');
                cardBody.className = 'card-body';

                var title = document.createElement('h3');
                var titleLink = document.createElement('a');
                titleLink.href = 'index.php?page=article&id=' + a.id;
                titleLink.textContent = a.title;
                title.appendChild(titleLink);

                var meta = document.createElement('div');
                meta.className = 'card-meta';

                var author = document.createElement('span');
                author.textContent = '✍️ ' + a.author_name;

                var date = document.createElement('span');
                date.textContent = '📅 ' + a.created_at.substring(0, 10);

                var likes = document.createElement('span');
                likes.textContent = '❤️ ' + a.like_count;

                meta.appendChild(author);
                meta.appendChild(date);

                if(a.category_name)
                {
                    var cat = document.createElement('span');
                    cat.className = 'badge';
                    cat.textContent = a.category_name;
                    meta.appendChild(cat);
                }

                meta.appendChild(likes);
                cardBody.appendChild(title);
                cardBody.appendChild(meta);
                card.appendChild(img);
                card.appendChild(cardBody);
                grid.appendChild(card);
            }
        }
        else
        {
            document.getElementById('articles-grid').innerHTML = '<p>Error loading articles.</p>';
        }
    }
    xhttp.open("GET", url, true);
    xhttp.send();
}

// =====================
// Live Search
// =====================
var searchTimer;

function liveSearch()
{
    var q = document.getElementById('search-input').value.trim();
    var dropdown = document.getElementById('search-dropdown');

    clearTimeout(searchTimer);

    if(q.length < 2)
    {
        dropdown.style.display = 'none';
        return;
    }

    searchTimer = setTimeout(function()
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function()
        {
            if(this.readyState == 4 && this.status == 200)
            {
                var results = JSON.parse(this.responseText);

                if(results.length == 0)
                {
                    dropdown.style.display = 'none';
                    return;
                }

                dropdown.innerHTML = "";

                for(var i = 0; i < results.length; i++)
                {
                    var r = results[i];

                    var link = document.createElement('a');
                    link.href = 'index.php?page=article&id=' + r.id;

                    var title = document.createTextNode(r.title + ' ');
                    var small = document.createElement('small');
                    small.style.color = '#888';
                    small.textContent = '— ' + r.author_name;

                    link.appendChild(title);
                    link.appendChild(small);
                    dropdown.appendChild(link);
                }

                dropdown.style.display = 'block';
            }
            else
            {
                dropdown.style.display = 'none';
            }
        }
        xhttp.open("GET", APP_ROOT + "/Api/search.php?q=" + encodeURIComponent(q), true);
        xhttp.send();
    }, 300);
}

document.addEventListener('click', function(e)
{
    var input = document.getElementById('search-input');
    var dropdown = document.getElementById('search-dropdown');
    if(input && !input.contains(e.target))
    {
        dropdown.style.display = 'none';
    }
});

// =====================
// Like / Unlike Toggle
// =====================
function toggleLike(articleId)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if(this.readyState !== 4) {
            return;
        }

        if(this.status == 200)
        {
            var data = JSON.parse(this.responseText);
            var btn = document.getElementById('like-btn');
            var countSpan = document.getElementById('like-count');

            if (countSpan) {
                countSpan.textContent = data.count;
            }

            if (btn) {
                btn.classList.toggle('liked', !!data.liked);
            }
        }
        else
        {
            alert('Something went wrong. Try again.');
        }
    }
    xhttp.open("POST", APP_ROOT + "/Api/likes.php", true);
    xhttp.setRequestHeader("content-type", "application/json");
    xhttp.send(JSON.stringify({article_id: articleId}));
}