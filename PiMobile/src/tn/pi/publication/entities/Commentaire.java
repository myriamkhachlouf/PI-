/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.entities;

import java.util.Date;

/**
 *
 * @author Mahmoud
 */
public class Commentaire {
    private int id,publication_id,postedby_id;
    private String contenu,Postername;
private Date created_at,updated_at; 

    public Commentaire(int publication_id,int postedby_id,String contenu) {
        this.publication_id=publication_id;
        this.postedby_id=postedby_id;
        this.contenu=contenu;
    }
    public String getPostername() {
        return Postername;
    }

    public void setPostername(String Postername) {
        this.Postername = Postername;
    }
    

    @Override
    public String toString() {
        return "Commentaire{" + "id=" + id + ", contenu=" + contenu + ", updated_at=" + updated_at + '}';
    }

    public Commentaire() {
    }

    @Override
    public int hashCode() {
        int hash = 7;
        hash = 67 * hash + this.id;
        return hash;
    }

    @Override
    public boolean equals(Object obj) {
        if (this == obj) {
            return true;
        }
        if (obj == null) {
            return false;
        }
        if (getClass() != obj.getClass()) {
            return false;
        }
        final Commentaire other = (Commentaire) obj;
        return true;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getPublication_id() {
        return publication_id;
    }

    public void setPublication_id(int publication_id) {
        this.publication_id = publication_id;
    }

    public int getPostedby_id() {
        return postedby_id;
    }

    public void setPostedby_id(int postedby_id) {
        this.postedby_id = postedby_id;
    }

    public String getContenu() {
        return contenu;
    }

    public void setContenu(String contenu) {
        this.contenu = contenu;
    }

    public Date getCreated_at() {
        return created_at;
    }

    public void setCreated_at(Date created_at) {
        this.created_at = created_at;
    }

    public Date getUpdated_at() {
        return updated_at;
    }

    public void setUpdated_at(Date updated_at) {
        this.updated_at = updated_at;
    }
}
