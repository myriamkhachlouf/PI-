/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.gui;

import com.codename1.components.ImageViewer;
import com.codename1.components.MultiButton;
import com.codename1.components.SpanLabel;
import com.codename1.l10n.ParseException;
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
 Form current;
 TextField tfComment = new TextField("","Comment");
    public void refresh(Publication pub){
       
        ps = new PostService();
        //setLayout(BoxLayout.y());
        List<Commentaire> comments = ps.GetPostComments(pub.getId());
        for (int i = 0; i < comments.size(); i++) {
            add(addCommentItem(comments.get(i),pub));
            SpanLabel nl= new SpanLabel("");
            add(nl);
        }
    }
    public  PostDetailsForm(Form previous, Publication pub) throws ParseException, IOException
    {
        current=previous;
        setTitle("Post Details");
        setLayout(BoxLayout.y());
       
        ImageViewer profilePic = new ImageViewer(MyApplication.theme.getImage("download (3).jpg").scaledWidth(Math.round(Display.getInstance().getDisplayWidth() / 0.5f)));
        SpanLabel views = new SpanLabel("views : "+pub.getViews());
        SpanLabel lbTitle = new SpanLabel("                       "+pub.getTitle());
        
       
        //lbTitle.setTextPosition(Component.CENTER);
        lbTitle.getAllStyles().setFgColor(0xff000);
        SpanLabel createdAt = new SpanLabel("Created At: "+pub.getCreatedAt());
        SpanLabel udatedAt = new SpanLabel("udated At: "+pub.getUpdatedAt());
        SpanLabel lbContent = new SpanLabel("Content: "+pub.getContenu());
       
        getToolbar().addCommandToRightBar("Retour", null, (evt) -> {
            previous.showBack();
            
        });
        
        addAll( views,lbTitle,profilePic,createdAt,udatedAt, lbContent);
        //add comment
       
       
        setLayout(BoxLayout.y());
       // TextField tfComment = new TextField("","Comment");
        tfComment.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
               if (tfComment.getText().length()<1)
                    Dialog.show("Alert", "Empty Field", new Command("OK"));
               else{     
               try {
                        Commentaire c = new Commentaire(pub.getId(),8,tfComment.getText());
                        if( PostService.getInstance().addComment(c)){
                                             
                             add(addCommentItem(c,pub));
            SpanLabel nl= new SpanLabel("");
            add(nl);
            tfComment.clear();
                               new PostDetailsForm(current,pub).show();

                            }
                        else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                            
               }
               catch (NumberFormatException e) {
                        Dialog.show("ERROR", "Status must be a number", new Command("OK"));
                    } catch (IOException ex) {
                       //Logger.getLogger(AddPostForm.class.getName()).log(Level.SEVERE, null, ex);
                        System.out.println(".actionPerformed() eror");
                        
                    } catch (ParseException ex) {
                       
                   }
               }
            }
        }); 
        addAll(tfComment);
      refresh(pub);
       }
    public Container addCommentItem(Commentaire c,Publication pub){
Container holder = new Container(BoxLayout.x());
        Container details = new Container(BoxLayout.y());
holder.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            holder.getUnselectedStyle().setBackgroundGradientEndColor(0xe7fdfa);
            holder.getUnselectedStyle().setBackgroundGradientStartColor(0xe7fdfa);
            details.getUnselectedStyle().setBackgroundType(Style.BACKGROUND_GRADIENT_RADIAL);
            details.getUnselectedStyle().setBackgroundGradientEndColor(0xe7fdfa);
            details.getUnselectedStyle().setBackgroundGradientStartColor(0xe7fdfa);
      
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
                   new PostDetailsForm(current,pub).show();
               } catch (Exception ex) {
                   Dialog.show("Error", "Comment isn't Deleted!", "OK", null);
               }
               
           } 
        });
        MultiButton updateIcon = new MultiButton("");
        updateIcon.setIcon(MyApplication.theme.getImage("icons8_edit_40px.png"));
       updateIcon.addActionListener(e->{ new UpdateComment(current,c).show();});
        
        holder.addAll(details,deleteIcon,updateIcon);
        
       // holder.setLeadComponent(lbTitle);
        
        return holder;
    }
    }

