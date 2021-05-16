/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.l10n.ParseException;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.events.ActionListener;
//import static com.sun.xml.internal.ws.api.message.Packet.Status.Request;
import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;
import tn.pi.publication.entities.Commentaire;


import tn.pi.publication.entities.Publication;
import tn.pi.publication.utils.Statics;

/**
 *
 * @author Mahmoud
 */
public class PostService {
     public ArrayList<Publication> posts;
     float views;
     public ArrayList<Commentaire> comments;
    public static PostService instance=null;
    public boolean resultOK;
    private ConnectionRequest req;

    public PostService() {
         req = new ConnectionRequest();
    }

    public static PostService getInstance() {
        if (instance == null) {
            instance = new PostService();
        }
        return instance;
    }
     public boolean updatePost(Publication p) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/UpdatePost"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("title",p.getTitle() );
        req.addArgument("contenu",p.getContenu() );
        req.addArgument("id",""+p.getId());
        req.addArgument("postedby_id",""+8 );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminÃ© de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle mÃ©thode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistrÃ© et donc Ã©xÃ©cutÃ© mÃªme si 
                la rÃ©ponse reÃ§ue correspond Ã  une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
    
    public boolean addPost(Publication p) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/add"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("title",p.getTitle() );
        req.addArgument("contenu",p.getContenu() );
        req.addArgument("postedby_id",""+8 );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminÃ© de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle mÃ©thode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistrÃ© et donc Ã©xÃ©cutÃ© mÃªme si 
                la rÃ©ponse reÃ§ue correspond Ã  une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
    public boolean deletePost(Publication p) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/DeletePost"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("id",""+p.getId() );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
      public boolean views(Publication pub) throws IOException{
       String url = Statics.BASE_URL+"/PostMobile/postviews";
       
         req.setUrl(url);
        req.setPost(true);
        req.addArgument("id",""+pub.getId() );
        req.addArgument("postedby_id",""+pub.getPostedby_id());
        req.addArgument("views",""+pub.getViews());
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
       
      }
    
     public ArrayList<Publication> getAllPosts(){
        String url = Statics.BASE_URL+"/PostMobile/AllPosts";
       
        req.setUrl(url);
        req.setPost(true);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    
                    posts = parsePosts(new String(req.getResponseData()));
                } catch (ParseException ex) {
                    
                }
                
                System.out.println(posts);
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return posts;
    }
     public ArrayList<Publication> searchPostX(String s){
        String url = Statics.BASE_URL+"/PostMobile/searchPosty";
       
        req.setUrl(url);
        req.setPost(true);
        req.addArgument("title",s);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                   
                    posts = parsePosts(new String(req.getResponseData()));
                } catch (ParseException ex) {
                    
                }
                
                System.out.println(posts);
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return posts;
    }
    
       public boolean deleteComment(Commentaire c) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/DeleteComment"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("id",""+c.getId() );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminÃ© de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle mÃ©thode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistrÃ© et donc Ã©xÃ©cutÃ© mÃªme si 
                la rÃ©ponse reÃ§ue correspond Ã  une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
       public boolean addComment(Commentaire c) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/addComment"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("publication_id",""+c.getPublication_id() );
        req.addArgument("contenu",c.getContenu() );
        req.addArgument("postedby_id",""+8 );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminÃ© de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle mÃ©thode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistrÃ© et donc Ã©xÃ©cutÃ© mÃªme si 
                la rÃ©ponse reÃ§ue correspond Ã  une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            //throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
       public boolean updateComment(Commentaire c) throws IOException {
       
        String url = Statics.BASE_URL + "/PostMobile/UpdateComment"; //crÃ©ation de l'URL
       
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.setPost(false);
        req.addArgument("id",""+c.getId() );
         req.addArgument("publication_id",""+c.getPublication_id() );
        req.addArgument("contenu",c.getContenu() );
        req.addArgument("postedby_id",""+8 );
                req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminÃ© de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle mÃ©thode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistrÃ© et donc Ã©xÃ©cutÃ© mÃªme si 
                la rÃ©ponse reÃ§ue correspond Ã  une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        byte[] data = req.getResponseData();
        if (data == null) {
            throw new IOException("Network Err");
        }
        
        JSONParser parser = new JSONParser();
        Map response = parser.parseJSON(new InputStreamReader(new ByteArrayInputStream(data), "UTF-8"));
        System.out.println("res" + response);
        List items = (List)response.get("items");
        return resultOK;
    }
       
     public ArrayList<Publication> parsePosts(String jsonText) throws ParseException{
        try {
            posts=new ArrayList<>();
            JSONParser j = new JSONParser();// Instanciation d'un objet JSONParser permettant le parsing du rÃ©sultat json

            /*
                On doit convertir notre rÃ©ponse texte en CharArray Ã  fin de
            permettre au JSONParser de la lire et la manipuler d'ou vient 
            l'utilitÃ© de new CharArrayReader(json.toCharArray())
            
            La mÃ©thode parse json retourne une MAP<String,Object> ou String est 
            la clÃ© principale de notre rÃ©sultat.
            Dans notre cas la clÃ© principale n'est pas dÃ©finie cela ne veux pas
            dire qu'elle est manquante mais plutÃ´t gardÃ©e Ã  la valeur par defaut
            qui est root.
            En fait c'est la clÃ© de l'objet qui englobe la totalitÃ© des objets 
                    c'est la clÃ© dÃ©finissant le tableau de tÃ¢ches.
            */
            Map<String,Object> postsListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
           
              /* Ici on rÃ©cupÃ¨re l'objet contenant notre liste dans une liste 
            d'objets json List<MAP<String,Object>> ou chaque Map est une tÃ¢che.               
            
            Le format Json impose que l'objet soit dÃ©finit sous forme
            de clÃ© valeur avec la valeur elle mÃªme peut Ãªtre un objet Json.
            Pour cela on utilise la structure Map comme elle est la structure la
            plus adÃ©quate en Java pour stocker des couples Key/Value.
            
            Pour le cas d'un tableau (Json Array) contenant plusieurs objets
            sa valeur est une liste d'objets Json, donc une liste de Map
            */
            List<Map<String,Object>> list = (List<Map<String,Object>>)postsListJson.get("root");
            //Parcourir la liste des tÃ¢ches Json
            for(Map<String,Object> obj : list){
                
                //CrÃ©ation des tÃ¢ches et rÃ©cupÃ©ration de leurs donnÃ©es
                 Publication  p = new Publication();
                float postedby_id = Float.parseFloat(obj.get("postedby_id").toString());
                float id = Float.parseFloat(obj.get("id").toString());
                
                float views = Float.parseFloat(obj.get("views").toString());
                p.setPostedby_id((int)postedby_id);
                p.setId((int)id);
                p.setViews((int)views);
                p.setTitle(obj.get("title").toString());
                p.setContenu(obj.get("contenu").toString());
 //////////////////
 p.setCreatedAt((String) obj.get("createdAt"));
 p.setUpdatedAt((String) obj.get("updatedAt"));
                //Ajouter la tÃ¢che extraite de la rÃ©ponse Json Ã  la liste
                posts.add(p);
                
            }
            
            
        } catch (IOException ex) {
            
        }
         /*
            A ce niveau on a pu rÃ©cupÃ©rer une liste des tÃ¢ches Ã  partir
        de la base de donnÃ©es Ã  travers un service web
        
        */
          
        return posts;
    }
      public ArrayList<Commentaire> parseComments(String jsonText,int pub_id) throws ParseException{
        try {
            System.out.println("aaaaaaaaaaa"+pub_id);
            comments=new ArrayList<>();
            JSONParser j = new JSONParser();// Instanciation d'un objet JSONParser permettant le parsing du rÃ©sultat json

            /*
                On doit convertir notre rÃ©ponse texte en CharArray Ã  fin de
            permettre au JSONParser de la lire et la manipuler d'ou vient 
            l'utilitÃ© de new CharArrayReader(json.toCharArray())
            
            La mÃ©thode parse json retourne une MAP<String,Object> ou String est 
            la clÃ© principale de notre rÃ©sultat.
            Dans notre cas la clÃ© principale n'est pas dÃ©finie cela ne veux pas
            dire qu'elle est manquante mais plutÃ´t gardÃ©e Ã  la valeur par defaut
            qui est root.
            En fait c'est la clÃ© de l'objet qui englobe la totalitÃ© des objets 
                    c'est la clÃ© dÃ©finissant le tableau de tÃ¢ches.
            */
            Map<String,Object> commentsListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            
              /* Ici on rÃ©cupÃ¨re l'objet contenant notre liste dans une liste 
            d'objets json List<MAP<String,Object>> ou chaque Map est une tÃ¢che.               
            
            Le format Json impose que l'objet soit dÃ©finit sous forme
            de clÃ© valeur avec la valeur elle mÃªme peut Ãªtre un objet Json.
            Pour cela on utilise la structure Map comme elle est la structure la
            plus adÃ©quate en Java pour stocker des couples Key/Value.
            
            Pour le cas d'un tableau (Json Array) contenant plusieurs objets
            sa valeur est une liste d'objets Json, donc une liste de Map
            */
            List<Map<String,Object>> list = (List<Map<String,Object>>)commentsListJson.get("root");
            
            //Parcourir la liste des tÃ¢ches Json
            for(Map<String,Object> obj : list){
                
                //CrÃ©ation des tÃ¢ches et rÃ©cupÃ©ration de leurs donnÃ©es
                 Commentaire  c = new Commentaire();
                float postedby_id = Float.parseFloat(obj.get("postedby_id").toString());
                float id = Float.parseFloat(obj.get("id").toString());
               // float views = Float.parseFloat(obj.get("views").toString());
                c.setPostedby_id((int)postedby_id);
                c.setId((int)id);
                c.setPublication_id(pub_id);
               // p.setViews((int)views);
                //c.setTitle(obj.get("title").toString());
                c.setContenu(obj.get("contenu").toString());
 
           /* float date1 = Float.parseFloat(obj.get("createdAt").toString());
            String created = new SimpleDateFormat("MM/dd/yyyy").format(new Date());
            float date2 = Float.parseFloat(obj.get("updatedAt").toString());
            String updated = new SimpleDateFormat("MM/dd/yyyy").format(new Date());
            
            Date createdAt= new SimpleDateFormat("MM/dd/yyyy").parse(created);
            Date updatedAt= new SimpleDateFormat("MM/dd/yyyy").parse(updated);
                p.setCreatedAt(createdAt);
                p.setUpdatedAt(updatedAt);*/
                //Ajouter la tÃ¢che extraite de la rÃ©ponse Json Ã  la liste
                comments.add(c);
                
            }
            
            
        } catch (IOException ex) {
            
        }
         /*
            A ce niveau on a pu rÃ©cupÃ©rer une liste des tÃ¢ches Ã  partir
        de la base de donnÃ©es Ã  travers un service web
        
        */
          
        return comments;
    }
    
   
      public ArrayList<Commentaire> GetPostComments(int pub_id){
          
           String url = Statics.BASE_URL+"/PostMobile/GetPostComments/"+pub_id;
       
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                   
                    comments = parseComments(new String(req.getResponseData()),pub_id);
                } catch (ParseException ex) {
                    
                }
                
                System.out.println(posts);
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return comments;
      }
      
}
     

