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
import com.codename1.ui.Command;
import com.codename1.ui.Component;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.URLImage;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.Style;
import java.io.IOException;
import java.util.List;
import tn.pi.publication.MyApplication;
import tn.pi.publication.entities.Commentaire;
import tn.pi.publication.entities.Publication;
import tn.pi.publication.services.PostService;

/**
 *
 * @author Mahmoud
 */
public class PostDetailsForm extends Form{
 private PostService ps;


    public  PostDetailsForm(Form previous, Publication pub)
    {
        setTitle("Post Details");
        setLayout(BoxLayout.y());
        
        ImageViewer profilePic = new ImageViewer(MyApplication.theme.getImage("download (3).jpg").scaledWidth(Math.round(Display.getInstance().getDisplayWidth() / 0.5f)));
        Label lbTitle = new Label("    "+pub.getTitle());
        lbTitle.setAutoSizeMode(focusScrolling);
        //lbTitle.setTextPosition(Component.CENTER);
        lbTitle.getAllStyles().setFgColor(0xff000);
        SpanLabel lbContent = new SpanLabel("Content: "+pub.getContenu());
       
        getToolbar().addCommandToRightBar("Retour", null, (evt) -> {
            previous.showBack();
        });
        
        addAll( lbTitle,profilePic, lbContent);
        //add comment
       
       
        setLayout(BoxLayout.y());
        TextField tfComment = new TextField("","Comment");
        tfComment.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
               if (tfComment.getText().length()<1)
                    Dialog.show("Alert", "Empty Field", new Command("OK"));
               else{     
               try {
                        Commentaire c = new Commentaire(pub.getId(),8,tfComment.getText());
                        if( PostService.getInstance().addComment(c))
                            Dialog.show("Success","Connection accepted",new Command("OK"));
                        else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                             ps = new PostService();
       
        List<Commentaire> comments = ps.GetPostComments(pub.getId());
        for (int i = 0; i < comments.size(); i++) {
            add(addCommentItem(comments.get(i)));
        }
               }
               catch (NumberFormatException e) {
                        Dialog.show("ERROR", "Status must be a number", new Command("OK"));
                    } catch (IOException ex) {
                       //Logger.getLogger(AddPostForm.class.getName()).log(Level.SEVERE, null, ex);
                        System.out.println(".actionPerformed() eror");
                        
                    }
               }
            }
        }); 
        addAll(tfComment);
        
        //show comments
         ps = new PostService();
        //setLayout(BoxLayout.y());
        List<Commentaire> comments = ps.GetPostComments(pub.getId());
        for (int i = 0; i < comments.size(); i++) {
            add(addCommentItem(comments.get(i)));
        }
       }
    public Container addCommentItem(Commentaire c){
Container holder = new Container(BoxLayout.x());
        Container details = new Container(BoxLayout.y());
holder.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            holder.getUnselectedStyle().setBackgroundGradientEndColor(0xFFBCCA);
            holder.getUnselectedStyle().setBackgroundGradientStartColor(0xFFBCCA);
            details.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            details.getUnselectedStyle().setBackgroundGradientEndColor(0xFFBCCA);
            details.getUnselectedStyle().setBackgroundGradientStartColor(0xFFBCCA);
      
        Label lbContent = new Label(c.getContenu());
        details.addAll(/*lbTitle,*/lbContent);
       
       // ImageViewer delete_icon = new ImageViewer(MyApplication.theme.getImage("icons8_delete_48px.png"));
        MultiButton deleteIcon = new MultiButton("");
        deleteIcon.setIcon(MyApplication.theme.getImage("icons8_delete_48px.png"));
       
       deleteIcon.addActionListener(e->{
           if(Dialog.show("Confirmation", "Delete this Comment?", "Yes", "No")){
               try {
                   ps.deleteComment(c);
                   System.out.println("Suppression OK !");
               } catch (Exception ex) {
                   Dialog.show("Error", "Comment isn't Deleted!", "OK", null);
               }
               
           } 
        });
        
        holder.addAll(details,deleteIcon);
        
       // holder.setLeadComponent(lbTitle);
        
        return holder;
    }
    }

