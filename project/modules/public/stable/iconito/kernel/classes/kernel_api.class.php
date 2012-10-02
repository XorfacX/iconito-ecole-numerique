<?php
/**
 * Kernel_API - Classes
 *
 * API de création, modification et suppression des éléments de base d'Ecole Numérique (villes, écoles, classes, etc.)
 * @package Iconito
 * @subpackage  Kernel
 * @version   $Id: kernel_api.class.php$
 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

class Kernel_API extends enicService
{

    public function __construct()
    {
        parent::__construct();
        $this->db =& enic::get('model');
    }


    /**
     * @param integer $id_grville Id du groupe de ville de rattachement
     * @param object $infos Informations sur la ville
     * @return integer Id de la ville
     */
    public function creerVille( $id_grville, $infos )
    {
        $test_grville = $this->db->query( 'SELECT id_grv FROM kernel_bu_groupe_villes WHERE id_grv='.$this->db->quote($id_grville) )->toArray();
        if( count($test_grville) == 0 ) {
            // Erreur : Groupe de ville inexistant
        }
        
        $test_canon = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE canon='.$this->db->quote($infos->nomCanonique) )->toArray();
        if( count($test_canon) > 0 ) {
            // Erreur : Nom canonique existant
        }
        
        $this->db->create(
            'kernel_bu_ville',
            array(
                'id_grville' => $this->db->quote($id_grville),
                'nom'        => $this->db->quote($infos->nom),
                'canon'      => $this->db->quote($infos->nomCanonique),
            )
        );
        
        return ($this->db->lastId);
    }
    
    /**
     * @param integer $id_ville Id de la ville
     */
    public function supprimerVille( $id_ville )
    {
        $this->db->delete( 'kernel_bu_ville', 'id_vi='.$this->db->quote($id_ville) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ville Id de la ville
     * @param object $infos Informations sur la ville
     */
    public function modifierVille( $id_ville, $infos )
    {
        $test_canon = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE canon='.$this->db->quote($infos->nomCanonique) )->toArray();
        if( count($test_canon) > 0 ) {
            // Erreur : Nom canonique existant
        }
        
        $this->db->query( 'UPDATE kernel_bu_ville SET nom='.$this->db->quote($infos->nom).', canon='.$this->db->quote($infos->nomCanonique).' WHERE id_vi='.$this->db->quote($id_ville) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ville Id de la ville de rattachement
     * @param object $infos Informations sur l'école
     * @return integer Id de l'école
     */
    public function creerEcole( $id_ville, $infos )
    {
        $test_ville = $this->db->query( 'SELECT id_vi FROM kernel_bu_ville WHERE id_vi='.$this->db->quote($id_ville) )->toArray();
        if( count($test_ville) == 0 ) {
            // Erreur : Ville inexistante
        }
        
        $this->db->create(
            'kernel_bu_ecole',
            array(
                'id_ville'    => $this->db->quote($id_ville),
                'nom'         => $this->db->quote($infos->nom),
                'RNE'         => $this->db->quote($infos->rne),
                'type'        => $this->db->quote($infos->type), // Primaire, Elémentaire, Maternelle
                'num_rue'     => $this->db->quote($infos->adresse->numRue),
                'num_seq'     => $this->db->quote($infos->adresse->numSeq), // bis, ter
                'adresse1'    => $this->db->quote($infos->adresse->adresse1),
                'adresse2'    => $this->db->quote($infos->adresse->adresse2),
                'code_postal' => $this->db->quote($infos->adresse->codePostal),
                'commune'     => $this->db->quote($infos->adresse->commune),
            )
        );
        
        return ($this->db->lastId);
    }

    /**
     * @param integer $id_ecole Id de l'école
     */
    public function supprimerEcole( $id_ecole )
    {
        $this->db->delete( 'kernel_bu_ecole', 'numero='.$this->db->quote($id_ecole) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ecole Id de l'école
     * @param object $infos Informations sur l'école
     */
    public function modifierEcole( $id_ecole, $infos )
    {
        $this->db->query( 'UPDATE kernel_bu_ecole SET nom='.$this->db->quote($infos->nom                ).',
                                                      RNE='.$this->db->quote($infos->rne                ).',
                                                     type='.$this->db->quote($infos->type               ).',
                                                  num_rue='.$this->db->quote($infos->adresse->numRue    ).',
                                                  num_seq='.$this->db->quote($infos->adresse->numSeq    ).',
                                                 adresse1='.$this->db->quote($infos->adresse->adresse1  ).',
                                                 adresse2='.$this->db->quote($infos->adresse->adresse2  ).',
                                              code_postal='.$this->db->quote($infos->adresse->codePostal).',
                                                  commune='.$this->db->quote($infos->adresse->commune   ).'
            WHERE numero='.$this->db->quote($id_ecole) );
        
        return (true);
    }
    
    /**
     * @param integer $id_ecole Id de l'école de rattachement
     * @param object $infos Informations sur la classe
     * @return integer Id de la classe
     */
    public function creerClasse( $id_ecole, $infos )
    {
        $test_ecole = $this->db->query( 'SELECT numero FROM kernel_bu_ecole WHERE numero='.$this->db->quote($id_ecole) )->toArray();
        if( count($test_ecole) == 0 ) {
            // Erreur : Ecole inexistante
        }
        
        $this->db->create(
            'kernel_bu_ecole_classe',
            array(
                'ecole'        => $this->db->quote($id_ecole),
                'nom'          => $this->db->quote($infos->nom),
                'annee_scol'   => $this->db->quote($infos->anneeScolaire),
                'is_validee'   => 1,
                'is_supprimee' => 0,
            )
        );

        $id_classe = $this->db->lastId;
        
        foreach( $infos->niveaux AS $niveau )
        {
            $this->db->create(
                'kernel_bu_ecole_classe_niveau',
                array(
                    'classe' => $id_classe,
                    'niveau' => $this->db->quote($niveau->niveau),
                    'type'   => $this->db->quote($niveau->type),
                )
            );
        }
        
        return ($id_classe);
    }
    
    /**
     * @param integer $id_classe Id de la classe
     */
    public function supprimerClasse( $id_classe )
    {
        $this->db->delete( 'kernel_bu_ecole_classe', 'id='.$this->db->quote($id_classe) );
        
        return (true);
    }
    
    /**
     * @param integer $id_classe Id de la classe
     * @param object $infos Informations sur la classe
     */
    public function modifierClasse( $id_classe, $infos )
    {
        $this->db->query( 'UPDATE kernel_bu_ecole_classe SET nom='.$this->db->quote($infos->nom).',
                                                      annee_scol='.$this->db->quote($infos->anneeScolaire).'
            WHERE id='.$this->db->quote($id_classe) );
        
        $this->db->delete( 'kernel_bu_ecole_classe_niveau', 'classe='.$this->db->quote($id_classe) );

        foreach( $infos->niveaux AS $niveau )
        {
            // $niveau->niveau
            // $niveau->type
            $this->db->create(
                'kernel_bu_ecole_classe_niveau',
                array(
                    'classe' => $id_classe,
                    'niveau' => $this->db->quote($niveau->niveau),
                    'type'   => $this->db->quote($niveau->type),
                )
            );
        }

        return (true);
    }
    
    
    public function creerDirecteur( $id_ecole, $infos )
    {
        
        return ($id_directeur);
    }
    
    public function supprimerDirecteur( $id_directeur )
    {
        
        return (true);
    }
    
    public function modifierDirecteur( $id_directeur, $infos )
    {
        
        return (true);
    }
    
    public function modifierDirecteurPassword( $id_directeur, $password )
    {
        
        return (true);
    }
    
    
    
}
