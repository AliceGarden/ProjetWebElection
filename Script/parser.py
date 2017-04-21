#!c:/python27/python.exe
#-*- coding: utf-8 -*-
from bs4 import BeautifulSoup
import re
import requests
import sys
 
 
url = str(sys.argv[1])
r = requests.get(url)
soup = BeautifulSoup(r.text,'xml')
 
 
Mentions = soup.find_all('Mentions')
Inscrits = soup.find_all('Inscrits')
Abstentions = soup.find_all('Abstentions')
Votants = soup.find_all('Votants')
Blancs = soup.find_all('Blancs')
Nuls = soup.find_all('Nuls')
Exprimes = soup.find_all('Exprimes')
 
def cam(m,i,a,v,b,n,e):
    mm = []
    ii = []
    aa = []
    vv = []
    bb = []
    nn = []
    ee = []
    tab = []
    for j in m:
        Mentions = j
    for j in i:
        ii.append("Inscrits")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                ii.append(B)
    for j in a:
        aa.append("Abstentions")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                aa.append(B)
    for j in v:
        vv.append("Votants")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                vv.append(B)
    for j in b:
        bb.append("Blancs")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                bb.append(B)
    for j in n:
        nn.append("Nuls")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                nn.append(B)
    for j in e:
        ee.append("Exprimes")
        for l,k in enumerate(j):
            if l % 2 !=0:
                B = str(k).replace("<Nombre>","")
                B = B.replace("</Nombre>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace("<RapportVotant>","")
                B = B.replace("</RapportVotant>","")
                B = B.replace(" ","")
                ee.append(B)
    tab.append(ii)
    tab.append(aa)
    tab.append(vv)
    tab.append(bb)
    tab.append(nn)
    tab.append(ee)
    jkjk = [0,1,2]
    Good = [3,4,5]
    Ggoood = [0,2]
    rr = []
    zz = []
    for j,i in enumerate(tab):
        if j in jkjk:
            rr.append(i)
        if j in Good:
            for vv,ll in enumerate(i):
                if vv in Ggoood:
                    zz.append(ll)
    finals = [zz[i:i+2] for i in range(0, len(zz), 2)]
    for i in finals:
        rr.append(i)
    return rr
 
 
 
Candidat = soup.find_all('Candidat')
tab1 = []
def dig():
    ee = []
    for i in Candidat:
        for l,k in enumerate(i):
            if l % 2 !=0:
                B = str(k).replace("<NumPanneauCand>","")
                B = B.replace("</NumPanneauCand>","")
                B = B.replace("<NomPsn>","")
                B = B.replace("</NomPsn>","")
                B = B.replace("<PrenomPsn>","")
                B = B.replace("</PrenomPsn>","")
                B = B.replace("<CivilitePsn>","")
                B = B.replace("</CivilitePsn>","")
                B = B.replace("<NbVoix>","")
                B = B.replace("</NbVoix>","")
                B = B.replace("<RapportExprime>","")
                B = B.replace("</RapportExprime>","")
                B = B.replace("<RapportInscrit>","")
                B = B.replace("</RapportInscrit>","")
                B = B.replace(" ","")
                ee.append(B)
 
    Good = [5]
    va = []
    final = [ee[i:i+7] for i in range(0, len(ee), 7)]
    for i in final:
        for k,lala in enumerate(i):
            if k in Good:
                va.append(lala)
    finals = [va[i:i+1] for i in range(0, len(va), 1)]
    flat=[x for sublist in finals for x in sublist]
    ok = []
    for i in flat:
        B = str(i).replace(',','.')
        ok.append(B)
    ok=[float(i) for i in ok]
    return ok
 
 
print cam(Mentions,Inscrits,Abstentions,Votants,Blancs,Nuls,Exprimes)
print dig()
  