using System;
using System.Collections.Generic;
using System.IO;
using System.Windows.Forms;

namespace kim_milyoner_olmak_ister
{
    public partial class Form1 : Form
    {
        private Label soruLabel;
        private List<Button> cevapButtons = new List<Button>();
        private List<Soru> sorular = new List<Soru>();
        private int soruIndex = 0;
        private bool jokerKullanildi = false;

        public Form1()
        {
            InitializeComponent();
            this.Load += Form1_Load;
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            SoruFormunuOlustur();
            JokerleriOlustur();
            string dosyaYolu = @"C:\Users\Uzay\source\repos\kim milyoner olmak ister\kim milyoner olmak ister\sorular.txt";
            SorulariOku(dosyaYolu);
            SoruyuGoster(sorular[soruIndex]);
        }

        private void SoruFormunuOlustur()
        {
            // Form özelliklerini ayarla
            this.Text = "Kim Milyoner Olmak İster?";
            this.Size = new System.Drawing.Size(600, 400);

            // soruLabel'i tanımla ve ayarla
            soruLabel = new Label();
            soruLabel.Text = "Bu bir soru metni olabilir.";
            soruLabel.Location = new System.Drawing.Point(20, 20);
            soruLabel.Size = new System.Drawing.Size(560, 50);
            this.Controls.Add(soruLabel);

            // Cevap düğmeleri oluştur
            string[] cevaplar = { "Cevap 1", "Cevap 2", "Cevap 3", "Cevap 4" };
            int y = 100;
            foreach (string cevap in cevaplar)
            {
                Button cevapButton = new Button();
                cevapButton.Text = cevap;
                cevapButton.Location = new System.Drawing.Point(20, y);
                cevapButton.Size = new System.Drawing.Size(560, 30);
                cevapButton.Click += CevapButton_Click;
                cevapButtons.Add(cevapButton);
                this.Controls.Add(cevapButton);
                y += 40;
            }
        }

        private void SoruyuGoster(Soru soru)
        {
            if (soru != null)
            {
                // Soru metnini güncelle
                soruLabel.Text = "Soru " + (soruIndex+ 1 ) + ": " + soru.Metin;

                // Cevap düğmelerinin metnini güncelle
                for (int i = 0; i < soru.Cevaplar.Length; i++)
                {
                    cevapButtons[i].Text = soru.Cevaplar[i];
                }

                

                
            }
        }








        private void TemizleVeYeniSoruGoster()
        {
            soruLabel.Text = "";

            // Cevap düğmelerinin metinlerini temizle
            foreach (Button cevapButton in cevapButtons)
            {
                cevapButton.Text = "";
            }

            if (soruIndex < sorular.Count)
            {
                soruIndex++;
                SoruyuGoster(sorular[soruIndex]);
            }
            else
            {
                MessageBox.Show("Tebrikler, tüm soruları doğru cevapladınız!");
                // Oyunu bitir veya başka bir işlem yap
            }
        }

        private void JokerleriOlustur()
        {
            // Joker düğmelerini oluştur
            Button telefonJokerButton = new Button();
            telefonJokerButton.Text = "Telefonla Ara";
            telefonJokerButton.Location = new System.Drawing.Point(20, 300);
            telefonJokerButton.Size = new System.Drawing.Size(120, 30);
            telefonJokerButton.Click += TelefonJokerButton_Click;
            this.Controls.Add(telefonJokerButton);

            Button seyirciJokerButton = new Button();
            seyirciJokerButton.Text = "Seyirciye Sor";
            seyirciJokerButton.Location = new System.Drawing.Point(160, 300);
            seyirciJokerButton.Size = new System.Drawing.Size(120, 30);
            seyirciJokerButton.Click += SeyirciJokerButton_Click;
            this.Controls.Add(seyirciJokerButton);

            Button ciftCevapJokerButton = new Button();
            ciftCevapJokerButton.Text = "Çift Cevap";
            ciftCevapJokerButton.Location = new System.Drawing.Point(300, 300);
            ciftCevapJokerButton.Size = new System.Drawing.Size(120, 30);
            ciftCevapJokerButton.Click += CiftCevapJokerButton_Click;
            this.Controls.Add(ciftCevapJokerButton);

            Button yariYariyaJokerButton = new Button();
            yariYariyaJokerButton.Text = "Yarı Yarıya";
            yariYariyaJokerButton.Location = new System.Drawing.Point(440, 300);
            yariYariyaJokerButton.Size = new System.Drawing.Size(120, 30);
            yariYariyaJokerButton.Click += YariYariyaJokerButton_Click;
            this.Controls.Add(yariYariyaJokerButton);
        }

        private void TelefonJokerButton_Click(object sender, EventArgs e)
        {
            if (!jokerKullanildi)
            {
                MessageBox.Show("Telefonla arama jokeri kullanıldı!");
                jokerKullanildi = true;
                // Telefonla arama işlemini gerçekleştir veya kullanıcıya bir ipucu ver
            }
            else
            {
                MessageBox.Show("Telefonla arama jokerini zaten kullandınız!");
            }
        }

        private void SeyirciJokerButton_Click(object sender, EventArgs e)
        {
            if (!jokerKullanildi)
            {
                MessageBox.Show("Seyirciye sor jokeri kullanıldı!");
                jokerKullanildi = true;
                // Seyirciye sor işlemini gerçekleştir veya kullanıcıya bir ipucu ver
            }
            else
            {
                MessageBox.Show("Seyirciye sor jokerini zaten kullandınız!");
            }
        }


        private void CiftCevapJokerButton_Click(object sender, EventArgs e)
        {
            if (!jokerKullanildi)
            {
                MessageBox.Show("Çift cevap jokeri kullanıldı!");
                jokerKullanildi = true;
                // Cift cevap işlemini gerçekleştir veya kullanıcıya bir ipucu ver
            }
            else
            {
                MessageBox.Show("Çift cevap jokerini zaten kullandınız!");
            }
        }

        private void YariYariyaJokerButton_Click(object sender, EventArgs e)
        {
            if (!jokerKullanildi)
            {
                MessageBox.Show("Yarı yarıya jokeri kullanıldı!");
                jokerKullanildi = true;
                // Yarı yarıya işlemini gerçekleştir veya kullanıcıya bir ipucu ver
            }
            else
            {
                MessageBox.Show("Yarı yarıya jokerini zaten kullandınız!");
            }
        }

        public class Soru
        {
            public string Metin { get; set; }
            public string[] Cevaplar { get; set; }
            public int DogruCevapIndex { get; set; }
        }

        private void SorulariOku(string dosyaYolu)
        {
            try
            {
                string[] satirlar = File.ReadAllLines(dosyaYolu);

                foreach (string satir in satirlar)
                {
                    string[] soruParcalari = satir.Split('|');

                    if (soruParcalari.Length == 3)
                    {
                        Soru soru = new Soru();
                        soru.Metin = soruParcalari[0];
                        soru.Cevaplar = soruParcalari[1].Split(';');

                        int dogruCevapIndex;
                        if (int.TryParse(soruParcalari[2], out dogruCevapIndex) && dogruCevapIndex >= 0 && dogruCevapIndex < soru.Cevaplar.Length)
                        {
                            soru.DogruCevapIndex = dogruCevapIndex;
                        }
                        else
                        {
                            MessageBox.Show("Doğru cevap indeksi hatalı: " + satir);
                            continue;
                        }

                        sorular.Add(soru);
                    }
                    else
                    {
                        MessageBox.Show("Satır doğru biçimde ayrıştırılamadı: " + satir);
                        continue;
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Sorular yüklenirken bir hata oluştu: " + ex.Message);
            }
        }




        private void CevapButton_Click(object sender, EventArgs e)
        {
            Button button = (Button)sender;
            string cevap = button.Text;
            Soru soru = sorular[soruIndex];
            if (cevap == soru.Cevaplar[soru.DogruCevapIndex])
            {
                MessageBox.Show("Doğru cevap!");
                TemizleVeYeniSoruGoster();
            }
            else
            {
                MessageBox.Show("Yanlış cevap!");
                // Oyunu bitir veya başka bir işlem yap
            }
        }
    }
}