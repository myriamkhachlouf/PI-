/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.gui;

import com.codename1.components.ImageViewer;
import com.codename1.components.MultiButton;
import com.codename1.components.SpanLabel;
import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.URLImage;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.Style;
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
            holder.getUnselectedStyle().setBackgroundGradientEndColor(0xFFBCCA);
            holder.getUnselectedStyle().setBackgroundGradientStartColor(0xFFBCCA);
            details.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            details.getUnselectedStyle().setBackgroundGradientEndColor(0xFFBCCA);
            details.getUnselectedStyle().setBackgroundGradientStartColor(0xFFBCCA);
       
        Label lbTitle = new Label(pub.getTitle().substring(0, Math.min(pub.getTitle().length(), 15))+"...");
       // Label lbModele = new Label(pub.getModele());
        Label lbContent = new Label(pub.getContenu().substring(0, Math.min(pub.getTitle().length(), 15))+"...");
        details.addAll(lbTitle,lbContent);
                        
        EncodedImage enc = EncodedImage.createFromImage(MyApplication.theme.getImage("download (3).jpg"), false);
        Image img = URLImage.createToStorage(enc,"Title:"+pub.getTitle(), pub.getContenu(), URLImage.RESIZE_SCALE);
        ImageViewer image = new ImageViewer(img);
       //delete icon
       // ImageViewer delete_icon = new ImageViewer(MyApplication.theme.getImage("icons8_delete_48px.png"));
        MultiButton deleteIcon = new MultiButton("");
        deleteIcon.setIcon(MyApplication.theme.getImage("icons8_delete_48px.png"));
       
       deleteIcon.addActionListener(e->{
           if(Dialog.show("Confirmation", "Delete this Post?", "Yes", "No")){
               try {
                   ps.deletePost(pub);
                   System.out.println("Insertion OK !");
               } catch (Exception ex) {
                   Dialog.show("Error", "Post isn't Deleted!", "OK", null);
               }
               
           } 
        });
        //
        Button btnViewMore = new Button("View More");
        btnViewMore.addActionListener(e -> new PostDetailsForm(current,pub).show());
        addAll(btnViewMore);    
        holder.addAll(image,details,deleteIcon);
        
        holder.setLeadComponent(lbTitle);
        
        return holder;
    }
}