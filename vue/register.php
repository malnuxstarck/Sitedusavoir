
<h1 class="titre">Inscription</h1>
<form method="post" action="" enctype="multipart/form-data" class="formulaire">
          
           <div class="formulaire-input">
             <label for="pseudo">* Pseudo </label> 
             <input name="pseudo" type="text" id="pseudo" required />(doit contenir entre 3 et 15 caractères, sans espace) 
           </p>

           <p>  
             <label for="password">* Mot de Passe </label>
             <input type="password" name="password" id="password" required/> 
           </p>

           <p>
             <label for="confirm">* Confirmer le mot de passe </label>
             <input type="password" name="confirm" id="confirm" required/>
           </p>

           <p>
             <label for="email">* Votre adresse Mail </label>
             <input type="text" name="email" id="email" required/>
           </p>

           <p>
             <label for="avatar">Choisissez votre avatar </label>
             <input type="file" name="avatar" id="avatar"/>(Taille max : 1mo)
           </p>

           <p>Les champs précédés d\'un * sont obligatoires</p>
           <p><input type="submit" value="Inscription"/></p>

         </form>