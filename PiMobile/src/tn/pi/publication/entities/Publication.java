/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.entities;

import java.util.ArrayList;
import java.util.Date;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

/**
 *
 * @author Mahmoud
 */
public class Publication {
     private int id,views,totalEntrpriseForChart,totalPostForChart;
      private int postedby_id;
    private String contenu,title,nomEntrepriseForChart;
    private String createdAt,UpdatedAt;
   public Set<Integer> AlreadySeenPost=new HashSet<Integer>();
 public Publication() {
    }
    public int getTotalEntrpriseForChart() {
        return totalEntrpriseForChart;
    }

    public void setTotalEntrpriseForChart(int totalEntrpriseForChart) {
        this.totalEntrpriseForChart = totalEntrpriseForChart;
    }

    public int getTotalPostForChart() {
        return totalPostForChart;
    }

    public void setTotalPostForChart(int totalPostForChart) {
        this.totalPostForChart = totalPostForChart;
    }

    public String getNomEntrepriseForChart() {
        return nomEntrepriseForChart;
    }

    public void setNomEntrepriseForChart(String nomEntrepriseForChart) {
        this.nomEntrepriseForChart = nomEntrepriseForChart;
    }
   

   
    public Set<Integer> getList() {
        return AlreadySeenPost;
    }

    public void setList(Set<Integer> list) {
        this.AlreadySeenPost = list;
    }

    public String getCreatedAt() {
        
        return createdAt;
    }

    public void setCreatedAt(String createdAt) {
        this.createdAt = createdAt;
    }

    public String getUpdatedAt() {
        return UpdatedAt;
    }

    public void setUpdatedAt(String UpdatedAt) {
        this.UpdatedAt = UpdatedAt;
    }
    
    @Override
    public String toString() {
        return "Publication{" + "id=" + id + ", views=" + views + ", postedby_id=" + postedby_id + ", contenu=" + contenu + ", title=" + title + '}';
    }

    public Publication(int postedby_id, String title, String contenu) {
        this.postedby_id = postedby_id;
        this.contenu = contenu;
        this.title = title;
    }

    
   
    public Publication(int id,int postedby_id,String title,String contenu) {
        this.id = id;
        this.views = views;
        this.postedby_id = postedby_id;
        this.contenu = contenu;
        this.title = title;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getViews() {
      
        return views;
    }

    public void setViews(int views) {
        this.views = views;
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

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    @Override
    public int hashCode() {
        int hash = 7;
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
        final Publication other = (Publication) obj;
        if (this.id != other.id) {
            return false;
        }
        return true;
    } 
}
