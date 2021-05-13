/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package tn.pi.publication.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.layouts.BoxLayout;

/**
 *
 * @author Mahmoud
 */
public class HomeForm extends Form {

    Form current;
    /*Garder traÃ§e de la Form en cours pour la passer en paramÃ¨tres 
    aux interfaces suivantes pour pouvoir y revenir plus tard en utilisant
    la mÃ©thode showBack*/
    
    public HomeForm() {
        current = this; //RÃ©cupÃ©ration de l'interface(Form) en cours
        setTitle("Home");
        setLayout(BoxLayout.y());

        add(new Label("Choose an option"));
        Button btnAddTask = new Button("Add Post");
        Button btnListTasks = new Button("List Posts");

        btnAddTask.addActionListener(e -> new AddPostForm(current).show());
        btnListTasks.addActionListener(e -> new ListPostsForm(current).show());
        addAll(btnAddTask, btnListTasks);

    }
    
}
