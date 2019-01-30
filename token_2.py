#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Mar 13 10:17:19 2018

@author: myriamelhelou
"""

"""
Ce code sert � tokeniser un texte pris en entr�e, il fournit le texte
tok�nis� en sortie. 
Nous consid�rons que un mot est un token.

"""

import re

class tokeniser:

    #cr�ation d'une variable globale
    def __init__(self):
        self.text=""

    def tokenise(self):
        doc=open("mettez le lien vers votre fichier � tokeniser",mode='r',encoding='utf-8')
        self.text=doc.read()
        doc.close()
        #on s�pare au niveau de l'espace
        self.text=re.sub(r' ',"\n",self.text,flags=re.I)
        
        self.text=re.sub(r'([l|s|d|u|c][\'])',r'\1\n',self.text,flags=re.I)
      
        #on s�pare au niveau des ponctuations
        self.text=re.sub(r'([,|.|:|!|;|�|)])',r'\n\1',self.text,flags=re.I)
        self.text=re.sub(r'([(|�])',r'\n\1\n',self.text,flags=re.I)
        
    
    def imprimer(self):
        print(self.text)
        
    def sortie_txt(self): 
    #write the result in a file
        filename="corpus_tok"
        fileOut=open(filename+'.txt',mode='w',encoding='utf-8')
        fileOut.write(self.text)
        fileOut.close
#--------------------------------------------------------------------------
    #execution des fonctions
if __name__ == '__main__':
    texte = tokeniser()
    texte.tokenise()
    #pour l'imprimer sur la console
    texte.imprimer()
    #pour avoir le texte tokenis� dans un fichier.txt
    #texte.sortie_txt()
