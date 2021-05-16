/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import java.io.IOException;
import tn.pi.publication.entities.Commentaire;
import tn.pi.publication.entities.Publication;
import tn.pi.publication.services.PostService;

/**
 *
 * @author Mahmoud
 */
public class ReasonDelete  extends Form{

    public ReasonDelete(Form previous,Publication pub) {
        setTitle("Reasons For Deleting this Post");
        setLayout(BoxLayout.y());
        TextField tfContent = new TextField();
        Button btnValider = new Button("Delete"); 
        
        btnValider.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
               if (tfContent.getText().length()<5)
                    Dialog.show("Alert", "Minimum 5 characters", new Command("OK"));
               else{     
               try {
                        
                        
                        if( PostService.getInstance().deletePost(pub,tfContent.getText())){
                            Dialog.show("Success","Post Deleted Successfully",new Command("OK"));
                           // new ListPostsForm(previous).show();
                            previous.showBack();
                                    }
                        else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                    } catch (NumberFormatException e) {
                        Dialog.show("ERROR", "Status must be a number", new Command("OK"));
                    } catch (IOException ex) {
                        System.out.println(".actionPerformed() eror");
                        
                    }
               }
     
            }
        });
        
        addAll(tfContent,btnValider);
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK
                , e-> previous.showBack()); // Revenir vers l'interface prÃ©cÃ©dente
                
    }
    
    
}
