/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.gui;

import com.codename1.components.ImageViewer;
import com.codename1.components.MultiButton;
import com.codename1.components.SpanLabel;
import static com.codename1.io.File.separator;
import com.codename1.l10n.ParseException;
import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Graphics;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.Toolbar;
import com.codename1.ui.URLImage;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.plaf.Style;
import java.io.IOException;
import java.util.List;
import tn.pi.publication.MyApplication;
import tn.pi.publication.entities.Publication;
import tn.pi.publication.services.PostService;

/**
 *
 * @author Mahmoud
 */
public class ListPostsForm extends Form{
 private PostService ps;
 Form current;
    public ListPostsForm(Form previous) {
  
       
        
      


      /////// 
        ps = new PostService();
        setTitle("List Posts");
        setLayout(BoxLayout.y());
        
         List<Publication> posts = ps.getAllPosts();
        for (int i = 0; i < posts.size(); i++) {
            add(addPostItem(posts.get(i)));
        }
       
       /* SpanLabel sp = new SpanLabel();
        sp.setText(PostService.getInstance().getAllPosts().toString());
        add(sp);*/
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e-> previous.showBack());   
}
    public Container addPostItem(Publication pub){
        current=this;
        Container holder = new Container(BoxLayout.x());
        Container details = new Container(BoxLayout.y());
        holder.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            holder.getUnselectedStyle().setBackgroundGradientEndColor(0xe7fdfa);
            holder.getUnselectedStyle().setBackgroundGradientStartColor(0xe7fdfa);
            details.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            details.getUnselectedStyle().setBackgroundGradientEndColor(0xe7fdfa);
            details.getUnselectedStyle().setBackgroundGradientStartColor(0xe7fdfa);
       
        Label lbTitle = new Label(pub.getTitle().substring(0, Math.min(pub.getTitle().length(), 20))+"...");
       // Label lbModele = new Label(pub.getModele());
       // Label lbContent = new Label(pub.getContenu().substring(0, Math.min(pub.getTitle().length(), 15))+"...");
        MultiButton btnViewMore = new MultiButton("View More");
         
       details.addAll(lbTitle,btnViewMore/*,lbContent*/);
          //addAll(btnViewMore);               
        EncodedImage enc = EncodedImage.createFromImage(MyApplication.theme.getImage("download (3).jpg"), false);
        Image img = URLImage.createToStorage(enc,"Title:"+pub.getTitle(), pub.getContenu(), URLImage.RESIZE_SCALE);
        ImageViewer image = new ImageViewer(img);
       //delete icon
       // ImageViewer delete_icon = new ImageViewer(MyApplication.theme.getImage("icons8_delete_48px.png"));
        MultiButton deleteIcon = new MultiButton("");
        deleteIcon.setIcon(MyApplication.theme.getImage("icons8_delete_48px.png"));
       //
       deleteIcon.addActionListener(e->{
           if(Dialog.show("Confirmation", "Delete this Post?", "Yes", "No")){
               try {
                  new ReasonDelete(current,pub).show();
                   
                   
               } catch (Exception ex) {
                   Dialog.show("Error", "Post isn't Deleted!", "OK", null);
               }
               
           } 
        });
       //
       MultiButton updateIcon = new MultiButton("");
        updateIcon.setIcon(MyApplication.theme.getImage("icons8_edit_40px.png"));
       updateIcon.addActionListener(e->{
           if(Dialog.show("Confirmation", "Update this Post?", "Yes", "No")){
               try {
                  new UpdatePost(current,pub).show();
               } catch (Exception ex) {
                   
               }
               
           } 
        });
        //
       // Button btnViewMore = new Button("View More");
        
        btnViewMore.addActionListener(e -> {
            try {
                Publication Post=new Publication();
                 ps = new PostService();
                 ps.views(pub);
                 List<Publication> posts = ps.getAllPosts();
        for (int i = 0; i < posts.size(); i++) {
            if (posts.get(i).getId()==pub.getId())
            Post=posts.get(i);
        }
            new PostDetailsForm(current,Post).show();
        }
             catch (ParseException ex) {
               
            } catch (IOException ex) {
               
            }
        });
        //addAll(btnViewMore);    
        holder.addAll(image,details,deleteIcon,updateIcon);
        
        holder.setLeadComponent(lbTitle);
        
        return holder;
    }
}