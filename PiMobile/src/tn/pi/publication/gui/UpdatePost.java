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
import com.codename1.ui.TextArea;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import java.io.IOException;
import tn.pi.publication.entities.Publication;
import tn.pi.publication.services.PostService;

/**
 *
 * @author Mahmoud
 */
public class UpdatePost extends Form{

    public UpdatePost(Form previous,Publication pub) {
        /*
        Le paramÃ¨tre previous dÃ©finit l'interface(Form) prÃ©cÃ©dente.
        Quelque soit l'interface faisant appel Ã  AddTask, on peut y revenir
        en utilisant le bouton back
        */
        setTitle("Update Post");
        setLayout(BoxLayout.y());
        
        TextField tfTitle = new TextField(pub.getTitle(),"");
        TextArea tfContent = new TextField(pub.getContenu(),10);
        Button btnValider = new Button("Update Post"); 
        
        btnValider.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
               if ((tfTitle.getText().length()<5)||(tfContent.getText().length()<5))
                    Dialog.show("Alert", "Minimum 5 characters", new Command("OK"));
               else{     
               try {
                        pub.setTitle(tfTitle.getText());
                        pub.setContenu(tfContent.getText());
                        if( PostService.getInstance().updatePost(pub)){
                            Dialog.show("Success","Post Updated",new Command("OK"));
                            previous.showBack();
                                    }
                        else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                    } catch (NumberFormatException e) {
                        Dialog.show("ERROR", "Status must be a number", new Command("OK"));
                    } catch (IOException ex) {
                       //Logger.getLogger(AddPostForm.class.getName()).log(Level.SEVERE, null, ex);
                        System.out.println(".actionPerformed() eror");
                        
                    }
               }
     
            }
        });
        
        addAll(tfTitle,tfContent,btnValider);
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK
                , e-> previous.showBack()); // Revenir vers l'interface prÃ©cÃ©dente
                
    }
    
}
