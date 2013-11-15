jQuery(document).ready(function($){

 $('.visualize').visualize({type: 'bar', height: '300px', width: '700px'});

});

function prepareAccountTypeSwitcher(switcherContainer, dataContainer, profiles){
    // On commence par ajouter le selecteur qui affiche tous les profils disponibles
    var select = $('<select></select>');

    var options = [];

    options.push($('<option value="">Tous</option>'));

    $.each(profiles, function(profile, libelle){
        options.push($('<option></option>').attr('value', profile).text(libelle));
    });

    select.append(options);

    var selectLabel = $('<label>Voir les statistiques de connexions pour le profil : </label>');
    selectLabel.append(select);

    switcherContainer.append(selectLabel);

    // On écoute le changement sur le sélecteur afin de masquer/afficher les parties concernées
    select.change(function(){
        var value = select.val();

        if ('' == value){
            value = 'ALL';
        }

        $('.linked-with-account-profile', dataContainer).hide().filter('.show-for-'+value).show();
    }).trigger('change');
}