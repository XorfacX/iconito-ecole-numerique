<div class="grid2-1">
    <div class="grid2">
        <div>
            <section class="photos pb0">
                <div class="slideshow" id="slider_photos">
                    {copixzone process=welcome|photos mode=js titre='' classeur=1 dossier=1 width=270 height=220 legendes=true}
                </div>
            </section>
            <section class="ecoles">
                <h1 class="">Les écoles</h1>
                {copixzone process=welcome|ecoles titre='' ajaxpopup=true colonnes=1 groupBy=ville grville=1 ville=0 kernellimiturl=1 dispFilter=0 dispHeader=0}
            </section>
            <a href="{copixurl dest="public||"}" class="blogsLink section">Voir les blogs</a>
        </div>
        
        <div>
            <section class="edito">
                {copixzone process=welcome|pages titre='' blog=edito nb=1 colonnes=1 content=true}
            </section>
        </div>
    </div>
    
    
        <section class="actualites">
            <h1 class="">Actualités</h1>
            {copixzone process=welcome|actualites blog=edito colonnes=1 chapo=1 nb=10}
        </section>
    
</div>
