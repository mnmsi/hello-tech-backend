<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $areas = [
            [
                "id" => "1",
                "city_id" => "34",
                "name" => "Amtali",
            ],
            [
                "id" => "2",
                "city_id" => "34",
                "name" => "Bamna",
            ],
            [
                "id" => "3",
                "city_id" => "34",
                "name" => "Barguna Sadar",
            ],
            [
                "id" => "4",
                "city_id" => "34",
                "name" => "Betagi",
            ],
            [
                "id" => "5",
                "city_id" => "34",
                "name" => "Patharghata",
            ],
            [
                "id" => "6",
                "city_id" => "34",
                "name" => "Taltali",
            ],
            [
                "id" => "7",
                "city_id" => "35",
                "name" => "Muladi",
            ],
            [
                "id" => "8",
                "city_id" => "35",
                "name" => "Babuganj",
            ],
            [
                "id" => "9",
                "city_id" => "35",
                "name" => "Agailjhara",
            ],
            [
                "id" => "10",
                "city_id" => "35",
                "name" => "Barisal Sadar",
            ],
            [
                "id" => "11",
                "city_id" => "35",
                "name" => "Bakerganj",
            ],
            [
                "id" => "12",
                "city_id" => "35",
                "name" => "Banaripara",
            ],
            [
                "id" => "13",
                "city_id" => "35",
                "name" => "Gaurnadi",
            ],
            [
                "id" => "14",
                "city_id" => "35",
                "name" => "Hizla",
            ],
            [
                "id" => "15",
                "city_id" => "35",
                "name" => "Mehendiganj",
            ],
            [
                "id" => "16",
                "city_id" => "35",
                "name" => "Wazirpur",
            ],
            [
                "id" => "17",
                "city_id" => "36",
                "name" => "Bhola Sadar",
            ],
            [
                "id" => "18",
                "city_id" => "36",
                "name" => "Burhanuddin",
            ],
            [
                "id" => "19",
                "city_id" => "36",
                "name" => "Char Fasson",
            ],
            [
                "id" => "20",
                "city_id" => "36",
                "name" => "Daulatkhan",
            ],
            [
                "id" => "21",
                "city_id" => "36",
                "name" => "Lalmohan",
            ],
            [
                "id" => "22",
                "city_id" => "36",
                "name" => "Manpura",
            ],
            [
                "id" => "23",
                "city_id" => "36",
                "name" => "Tazumuddin",
            ],
            [
                "id" => "24",
                "city_id" => "37",
                "name" => "Jhalokati Sadar",
            ],
            [
                "id" => "25",
                "city_id" => "37",
                "name" => "Kathalia",
            ],
            [
                "id" => "26",
                "city_id" => "37",
                "name" => "Nalchity",
            ],
            [
                "id" => "27",
                "city_id" => "37",
                "name" => "Rajapur",
            ],
            [
                "id" => "28",
                "city_id" => "38",
                "name" => "Bauphal",
            ],
            [
                "id" => "29",
                "city_id" => "38",
                "name" => "Dashmina",
            ],
            [
                "id" => "30",
                "city_id" => "38",
                "name" => "Galachipa",
            ],
            [
                "id" => "31",
                "city_id" => "38",
                "name" => "Kalapara",
            ],
            [
                "id" => "32",
                "city_id" => "38",
                "name" => "Mirzaganj",
            ],
            [
                "id" => "33",
                "city_id" => "38",
                "name" => "Patuakhali Sadar",
            ],
            [
                "id" => "34",
                "city_id" => "38",
                "name" => "Dumki",
            ],
            [
                "id" => "35",
                "city_id" => "38",
                "name" => "Rangabali",
            ],
            [
                "id" => "36",
                "city_id" => "39",
                "name" => "Bhandaria",
            ],
            [
                "id" => "37",
                "city_id" => "39",
                "name" => "Kaukhali",
            ],
            [
                "id" => "38",
                "city_id" => "39",
                "name" => "Mathbaria",
            ],
            [
                "id" => "39",
                "city_id" => "39",
                "name" => "Nazirpur",
            ],
            [
                "id" => "40",
                "city_id" => "39",
                "name" => "Nesarabad",
            ],
            [
                "id" => "41",
                "city_id" => "39",
                "name" => "Pirojpur Sadar",
            ],
            [
                "id" => "42",
                "city_id" => "39",
                "name" => "Zianagar",
            ],
            [
                "id" => "43",
                "city_id" => "40",
                "name" => "Bandarban Sadar",
            ],
            [
                "id" => "44",
                "city_id" => "40",
                "name" => "Thanchi",
            ],
            [
                "id" => "45",
                "city_id" => "40",
                "name" => "Lama",
            ],
            [
                "id" => "46",
                "city_id" => "40",
                "name" => "Naikhongchhari",
            ],
            [
                "id" => "47",
                "city_id" => "40",
                "name" => "Ali kadam",
            ],
            [
                "id" => "48",
                "city_id" => "40",
                "name" => "Rowangchhari",
            ],
            [
                "id" => "49",
                "city_id" => "40",
                "name" => "Ruma",
            ],
            [
                "id" => "50",
                "city_id" => "41",
                "name" => "Brahmanbaria Sadar",
            ],
            [
                "id" => "51",
                "city_id" => "41",
                "name" => "Ashuganj",
            ],
            [
                "id" => "52",
                "city_id" => "41",
                "name" => "Nasirnagar",
            ],
            [
                "id" => "53",
                "city_id" => "41",
                "name" => "Nabinagar",
            ],
            [
                "id" => "54",
                "city_id" => "41",
                "name" => "Sarail",
            ],
            [
                "id" => "55",
                "city_id" => "41",
                "name" => "Shahbazpur Town",
            ],
            [
                "id" => "56",
                "city_id" => "41",
                "name" => "Kasba",
            ],
            [
                "id" => "57",
                "city_id" => "41",
                "name" => "Akhaura",
            ],
            [
                "id" => "58",
                "city_id" => "41",
                "name" => "Bancharampur",
            ],
            [
                "id" => "59",
                "city_id" => "41",
                "name" => "Bijoynagar",
            ],
            [
                "id" => "60",
                "city_id" => "42",
                "name" => "Chandpur Sadar",
            ],
            [
                "id" => "61",
                "city_id" => "42",
                "name" => "Faridganj",
            ],
            [
                "id" => "62",
                "city_id" => "42",
                "name" => "Haimchar",
            ],
            [
                "id" => "63",
                "city_id" => "42",
                "name" => "Haziganj",
            ],
            [
                "id" => "64",
                "city_id" => "42",
                "name" => "Kachua",
            ],
            [
                "id" => "65",
                "city_id" => "42",
                "name" => "Matlab Uttar",
            ],
            [
                "id" => "66",
                "city_id" => "42",
                "name" => "Matlab Dakkhin",
            ],
            [
                "id" => "67",
                "city_id" => "42",
                "name" => "Shahrasti",
            ],
            [
                "id" => "68",
                "city_id" => "43",
                "name" => "Anwara",
            ],
            [
                "id" => "69",
                "city_id" => "43",
                "name" => "Banshkhali",
            ],
            [
                "id" => "70",
                "city_id" => "43",
                "name" => "Boalkhali",
            ],
            [
                "id" => "71",
                "city_id" => "43",
                "name" => "Chandanaish",
            ],
            [
                "id" => "72",
                "city_id" => "43",
                "name" => "Fatikchhari",
            ],
            [
                "id" => "73",
                "city_id" => "43",
                "name" => "Hathazari",
            ],
            [
                "id" => "74",
                "city_id" => "43",
                "name" => "Lohagara",
            ],
            [
                "id" => "75",
                "city_id" => "43",
                "name" => "Mirsharai",
            ],
            [
                "id" => "76",
                "city_id" => "43",
                "name" => "Patiya",
            ],
            [
                "id" => "77",
                "city_id" => "43",
                "name" => "Rangunia",
            ],
            [
                "id" => "78",
                "city_id" => "43",
                "name" => "Raozan",
            ],
            [
                "id" => "79",
                "city_id" => "43",
                "name" => "Sandwip",
            ],
            [
                "id" => "80",
                "city_id" => "43",
                "name" => "Satkania",
            ],
            [
                "id" => "81",
                "city_id" => "43",
                "name" => "Sitakunda",
            ],
            [
                "id" => "82",
                "city_id" => "44",
                "name" => "Barura",
            ],
            [
                "id" => "83",
                "city_id" => "44",
                "name" => "Brahmanpara",
            ],
            [
                "id" => "84",
                "city_id" => "44",
                "name" => "Burichong",
            ],
            [
                "id" => "85",
                "city_id" => "44",
                "name" => "Chandina",
            ],
            [
                "id" => "86",
                "city_id" => "44",
                "name" => "Chauddagram",
            ],
            [
                "id" => "87",
                "city_id" => "44",
                "name" => "Daudkandi",
            ],
            [
                "id" => "88",
                "city_id" => "44",
                "name" => "Debidwar",
            ],
            [
                "id" => "89",
                "city_id" => "44",
                "name" => "Homna",
            ],
            [
                "id" => "90",
                "city_id" => "44",
                "name" => "Comilla Sadar",
            ],
            [
                "id" => "91",
                "city_id" => "44",
                "name" => "Laksam",
            ],
            [
                "id" => "92",
                "city_id" => "44",
                "name" => "Monohorgonj",
            ],
            [
                "id" => "93",
                "city_id" => "44",
                "name" => "Meghna",
            ],
            [
                "id" => "94",
                "city_id" => "44",
                "name" => "Muradnagar",
            ],
            [
                "id" => "95",
                "city_id" => "44",
                "name" => "Nangalkot",
            ],
            [
                "id" => "96",
                "city_id" => "44",
                "name" => "Comilla Sadar South",
            ],
            [
                "id" => "97",
                "city_id" => "44",
                "name" => "Titas",
            ],
            [
                "id" => "98",
                "city_id" => "45",
                "name" => "Chakaria",
            ],
            [
                "id" => "100",
                "city_id" => "45",
                "name" => "{{198}}\'\'{{199}}",
            ],
            [
                "id" => "101",
                "city_id" => "45",
                "name" => "Kutubdia",
            ],
            [
                "id" => "102",
                "city_id" => "45",
                "name" => "Maheshkhali",
            ],
            [
                "id" => "103",
                "city_id" => "45",
                "name" => "Ramu",
            ],
            [
                "id" => "104",
                "city_id" => "45",
                "name" => "Teknaf",
            ],
            [
                "id" => "105",
                "city_id" => "45",
                "name" => "Ukhia",
            ],
            [
                "id" => "106",
                "city_id" => "45",
                "name" => "Pekua",
            ],
            [
                "id" => "107",
                "city_id" => "46",
                "name" => "Feni Sadar",
            ],
            [
                "id" => "108",
                "city_id" => "46",
                "name" => "Chagalnaiya",
            ],
            [
                "id" => "109",
                "city_id" => "46",
                "name" => "Daganbhyan",
            ],
            [
                "id" => "110",
                "city_id" => "46",
                "name" => "Parshuram",
            ],
            [
                "id" => "111",
                "city_id" => "46",
                "name" => "Fhulgazi",
            ],
            [
                "id" => "112",
                "city_id" => "46",
                "name" => "Sonagazi",
            ],
            [
                "id" => "113",
                "city_id" => "47",
                "name" => "Dighinala",
            ],
            [
                "id" => "114",
                "city_id" => "47",
                "name" => "Khagrachhari",
            ],
            [
                "id" => "115",
                "city_id" => "47",
                "name" => "Lakshmichhari",
            ],
            [
                "id" => "116",
                "city_id" => "47",
                "name" => "Mahalchhari",
            ],
            [
                "id" => "117",
                "city_id" => "47",
                "name" => "Manikchhari",
            ],
            [
                "id" => "118",
                "city_id" => "47",
                "name" => "Matiranga",
            ],
            [
                "id" => "119",
                "city_id" => "47",
                "name" => "Panchhari",
            ],
            [
                "id" => "120",
                "city_id" => "47",
                "name" => "Ramgarh",
            ],
            [
                "id" => "121",
                "city_id" => "48",
                "name" => "Lakshmipur Sadar",
            ],
            [
                "id" => "122",
                "city_id" => "48",
                "name" => "Raipur",
            ],
            [
                "id" => "123",
                "city_id" => "48",
                "name" => "Ramganj",
            ],
            [
                "id" => "124",
                "city_id" => "48",
                "name" => "Ramgati",
            ],
            [
                "id" => "125",
                "city_id" => "48",
                "name" => "Komol Nagar",
            ],
            [
                "id" => "126",
                "city_id" => "49",
                "name" => "Noakhali Sadar",
            ],
            [
                "id" => "127",
                "city_id" => "49",
                "name" => "Begumganj",
            ],
            [
                "id" => "128",
                "city_id" => "49",
                "name" => "Chatkhil",
            ],
            [
                "id" => "129",
                "city_id" => "49",
                "name" => "Companyganj",
            ],
            [
                "id" => "130",
                "city_id" => "49",
                "name" => "Shenbag",
            ],
            [
                "id" => "131",
                "city_id" => "49",
                "name" => "Hatia",
            ],
            [
                "id" => "132",
                "city_id" => "49",
                "name" => "Kobirhat",
            ],
            [
                "id" => "133",
                "city_id" => "49",
                "name" => "Sonaimuri",
            ],
            [
                "id" => "134",
                "city_id" => "49",
                "name" => "Suborno Char",
            ],
            [
                "id" => "135",
                "city_id" => "50",
                "name" => "Rangamati Sadar",
            ],
            [
                "id" => "136",
                "city_id" => "50",
                "name" => "Belaichhari",
            ],
            [
                "id" => "137",
                "city_id" => "50",
                "name" => "Bagaichhari",
            ],
            [
                "id" => "138",
                "city_id" => "50",
                "name" => "Barkal",
            ],
            [
                "id" => "139",
                "city_id" => "50",
                "name" => "Juraichhari",
            ],
            [
                "id" => "140",
                "city_id" => "50",
                "name" => "Rajasthali",
            ],
            [
                "id" => "141",
                "city_id" => "50",
                "name" => "Kaptai",
            ],
            [
                "id" => "142",
                "city_id" => "50",
                "name" => "Langadu",
            ],
            [
                "id" => "143",
                "city_id" => "50",
                "name" => "Nannerchar",
            ],
            [
                "id" => "144",
                "city_id" => "50",
                "name" => "Kaukhali",
            ],
            [
                "id" => "145",
                "city_id" => "1",
                "name" => "Dhamrai",
            ],
            [
                "id" => "146",
                "city_id" => "1",
                "name" => "Dohar",
            ],
            [
                "id" => "147",
                "city_id" => "1",
                "name" => "Keraniganj",
            ],
            [
                "id" => "148",
                "city_id" => "1",
                "name" => "Nawabganj",
            ],
            [
                "id" => "149",
                "city_id" => "1",
                "name" => "Savar",
            ],
            [
                "id" => "150",
                "city_id" => "2",
                "name" => "Faridpur Sadar",
            ],
            [
                "id" => "151",
                "city_id" => "2",
                "name" => "Boalmari",
            ],
            [
                "id" => "152",
                "city_id" => "2",
                "name" => "Alfadanga",
            ],
            [
                "id" => "153",
                "city_id" => "2",
                "name" => "Madhukhali",
            ],
            [
                "id" => "154",
                "city_id" => "2",
                "name" => "Bhanga",
            ],
            [
                "id" => "155",
                "city_id" => "2",
                "name" => "Nagarkanda",
            ],
            [
                "id" => "156",
                "city_id" => "2",
                "name" => "Charbhadrasan",
            ],
            [
                "id" => "157",
                "city_id" => "2",
                "name" => "Sadarpur",
            ],
            [
                "id" => "158",
                "city_id" => "2",
                "name" => "Shaltha",
            ],
            [
                "id" => "159",
                "city_id" => "3",
                "name" => "Gazipur Sadar-Joydebpur",
            ],
            [
                "id" => "160",
                "city_id" => "3",
                "name" => "Kaliakior",
            ],
            [
                "id" => "161",
                "city_id" => "3",
                "name" => "Kapasia",
            ],
            [
                "id" => "162",
                "city_id" => "3",
                "name" => "Sripur",
            ],
            [
                "id" => "163",
                "city_id" => "3",
                "name" => "Kaliganj",
            ],
            [
                "id" => "164",
                "city_id" => "3",
                "name" => "Tongi",
            ],
            [
                "id" => "165",
                "city_id" => "4",
                "name" => "Gopalganj Sadar",
            ],
            [
                "id" => "166",
                "city_id" => "4",
                "name" => "Kashiani",
            ],
            [
                "id" => "167",
                "city_id" => "4",
                "name" => "Kotalipara",
            ],
            [
                "id" => "168",
                "city_id" => "4",
                "name" => "Muksudpur",
            ],
            [
                "id" => "169",
                "city_id" => "4",
                "name" => "Tungipara",
            ],
            [
                "id" => "170",
                "city_id" => "5",
                "name" => "Dewanganj",
            ],
            [
                "id" => "171",
                "city_id" => "5",
                "name" => "Baksiganj",
            ],
            [
                "id" => "172",
                "city_id" => "5",
                "name" => "Islampur",
            ],
            [
                "id" => "173",
                "city_id" => "5",
                "name" => "Jamalpur Sadar",
            ],
            [
                "id" => "174",
                "city_id" => "5",
                "name" => "Madarganj",
            ],
            [
                "id" => "175",
                "city_id" => "5",
                "name" => "Melandaha",
            ],
            [
                "id" => "176",
                "city_id" => "5",
                "name" => "Sarishabari",
            ],
            [
                "id" => "177",
                "city_id" => "5",
                "name" => "Narundi Police I.C",
            ],
            [
                "id" => "178",
                "city_id" => "6",
                "name" => "Astagram",
            ],
            [
                "id" => "179",
                "city_id" => "6",
                "name" => "Bajitpur",
            ],
            [
                "id" => "180",
                "city_id" => "6",
                "name" => "Bhairab",
            ],
            [
                "id" => "181",
                "city_id" => "6",
                "name" => "Hossainpur",
            ],
            [
                "id" => "182",
                "city_id" => "6",
                "name" => "Itna",
            ],
            [
                "id" => "183",
                "city_id" => "6",
                "name" => "Karimganj",
            ],
            [
                "id" => "184",
                "city_id" => "6",
                "name" => "Katiadi",
            ],
            [
                "id" => "185",
                "city_id" => "6",
                "name" => "Kishoreganj Sadar",
            ],
            [
                "id" => "186",
                "city_id" => "6",
                "name" => "Kuliarchar",
            ],
            [
                "id" => "187",
                "city_id" => "6",
                "name" => "Mithamain",
            ],
            [
                "id" => "188",
                "city_id" => "6",
                "name" => "Nikli",
            ],
            [
                "id" => "189",
                "city_id" => "6",
                "name" => "Pakundia",
            ],
            [
                "id" => "190",
                "city_id" => "6",
                "name" => "Tarail",
            ],
            [
                "id" => "191",
                "city_id" => "7",
                "name" => "Madaripur Sadar",
            ],
            [
                "id" => "192",
                "city_id" => "7",
                "name" => "Kalkini",
            ],
            [
                "id" => "193",
                "city_id" => "7",
                "name" => "Rajoir",
            ],
            [
                "id" => "194",
                "city_id" => "7",
                "name" => "Shibchar",
            ],
            [
                "id" => "195",
                "city_id" => "8",
                "name" => "Manikganj Sadar",
            ],
            [
                "id" => "196",
                "city_id" => "8",
                "name" => "Singair",
            ],
            [
                "id" => "197",
                "city_id" => "8",
                "name" => "Shibalaya",
            ],
            [
                "id" => "198",
                "city_id" => "8",
                "name" => "Saturia",
            ],
            [
                "id" => "199",
                "city_id" => "8",
                "name" => "Harirampur",
            ],
            [
                "id" => "200",
                "city_id" => "8",
                "name" => "Ghior",
            ],
            [
                "id" => "201",
                "city_id" => "8",
                "name" => "Daulatpur",
            ],
            [
                "id" => "202",
                "city_id" => "9",
                "name" => "Lohajang",
            ],
            [
                "id" => "203",
                "city_id" => "9",
                "name" => "Sreenagar",
            ],
            [
                "id" => "204",
                "city_id" => "9",
                "name" => "Munshiganj Sadar",
            ],
            [
                "id" => "205",
                "city_id" => "9",
                "name" => "Sirajdikhan",
            ],
            [
                "id" => "206",
                "city_id" => "9",
                "name" => "Tongibari",
            ],
            [
                "id" => "207",
                "city_id" => "9",
                "name" => "Gazaria",
            ],
            [
                "id" => "208",
                "city_id" => "10",
                "name" => "Bhaluka",
            ],
            [
                "id" => "209",
                "city_id" => "10",
                "name" => "Trishal",
            ],
            [
                "id" => "210",
                "city_id" => "10",
                "name" => "Haluaghat",
            ],
            [
                "id" => "211",
                "city_id" => "10",
                "name" => "Muktagachha",
            ],
            [
                "id" => "212",
                "city_id" => "10",
                "name" => "Dhobaura",
            ],
            [
                "id" => "213",
                "city_id" => "10",
                "name" => "Fulbaria",
            ],
            [
                "id" => "214",
                "city_id" => "10",
                "name" => "Gaffargaon",
            ],
            [
                "id" => "215",
                "city_id" => "10",
                "name" => "Gauripur",
            ],
            [
                "id" => "216",
                "city_id" => "10",
                "name" => "Ishwarganj",
            ],
            [
                "id" => "217",
                "city_id" => "10",
                "name" => "Mymensingh Sadar",
            ],
            [
                "id" => "218",
                "city_id" => "10",
                "name" => "Nandail",
            ],
            [
                "id" => "219",
                "city_id" => "10",
                "name" => "Phulpur",
            ],
            [
                "id" => "220",
                "city_id" => "11",
                "name" => "Araihazar",
            ],
            [
                "id" => "221",
                "city_id" => "11",
                "name" => "Sonargaon",
            ],
            [
                "id" => "222",
                "city_id" => "11",
                "name" => "Bandar",
            ],
            [
                "id" => "223",
                "city_id" => "11",
                "name" => "Naryanganj Sadar",
            ],
            [
                "id" => "224",
                "city_id" => "11",
                "name" => "Rupganj",
            ],
            [
                "id" => "225",
                "city_id" => "11",
                "name" => "Siddirgonj",
            ],
            [
                "id" => "226",
                "city_id" => "12",
                "name" => "Belabo",
            ],
            [
                "id" => "227",
                "city_id" => "12",
                "name" => "Monohardi",
            ],
            [
                "id" => "228",
                "city_id" => "12",
                "name" => "Narsingdi Sadar",
            ],
            [
                "id" => "229",
                "city_id" => "12",
                "name" => "Palash",
            ],
            [
                "id" => "230",
                "city_id" => "12",
                "name" => "Raipura, Narsingdi",
            ],
            [
                "id" => "231",
                "city_id" => "12",
                "name" => "Shibpur",
            ],
            [
                "id" => "232",
                "city_id" => "13",
                "name" => "Kendua Upazilla",
            ],
            [
                "id" => "233",
                "city_id" => "13",
                "name" => "Atpara Upazilla",
            ],
            [
                "id" => "234",
                "city_id" => "13",
                "name" => "Barhatta Upazilla",
            ],
            [
                "id" => "235",
                "city_id" => "13",
                "name" => "Durgapur Upazilla",
            ],
            [
                "id" => "236",
                "city_id" => "13",
                "name" => "Kalmakanda Upazilla",
            ],
            [
                "id" => "237",
                "city_id" => "13",
                "name" => "Madan Upazilla",
            ],
            [
                "id" => "238",
                "city_id" => "13",
                "name" => "Mohanganj Upazilla",
            ],
            [
                "id" => "239",
                "city_id" => "13",
                "name" => "Netrakona-S Upazilla",
            ],
            [
                "id" => "240",
                "city_id" => "13",
                "name" => "Purbadhala Upazilla",
            ],
            [
                "id" => "241",
                "city_id" => "13",
                "name" => "Khaliajuri Upazilla",
            ],
            [
                "id" => "242",
                "city_id" => "14",
                "name" => "Baliakandi",
            ],
            [
                "id" => "243",
                "city_id" => "14",
                "name" => "Goalandaghat",
            ],
            [
                "id" => "244",
                "city_id" => "14",
                "name" => "Pangsha",
            ],
            [
                "id" => "245",
                "city_id" => "14",
                "name" => "Kalukhali",
            ],
            [
                "id" => "246",
                "city_id" => "14",
                "name" => "Rajbari Sadar",
            ],
            [
                "id" => "247",
                "city_id" => "15",
                "name" => "Shariatpur Sadar -Palong",
            ],
            [
                "id" => "248",
                "city_id" => "15",
                "name" => "Damudya",
            ],
            [
                "id" => "249",
                "city_id" => "15",
                "name" => "Naria",
            ],
            [
                "id" => "250",
                "city_id" => "15",
                "name" => "Jajira",
            ],
            [
                "id" => "251",
                "city_id" => "15",
                "name" => "Bhedarganj",
            ],
            [
                "id" => "252",
                "city_id" => "15",
                "name" => "Gosairhat",
            ],
            [
                "id" => "253",
                "city_id" => "16",
                "name" => "Jhenaigati",
            ],
            [
                "id" => "254",
                "city_id" => "16",
                "name" => "Nakla",
            ],
            [
                "id" => "255",
                "city_id" => "16",
                "name" => "Nalitabari",
            ],
            [
                "id" => "256",
                "city_id" => "16",
                "name" => "Sherpur Sadar",
            ],
            [
                "id" => "257",
                "city_id" => "16",
                "name" => "Sreebardi",
            ],
            [
                "id" => "258",
                "city_id" => "17",
                "name" => "Tangail Sadar",
            ],
            [
                "id" => "259",
                "city_id" => "17",
                "name" => "Sakhipur",
            ],
            [
                "id" => "260",
                "city_id" => "17",
                "name" => "Basail",
            ],
            [
                "id" => "261",
                "city_id" => "17",
                "name" => "Madhupur",
            ],
            [
                "id" => "262",
                "city_id" => "17",
                "name" => "Ghatail",
            ],
            [
                "id" => "263",
                "city_id" => "17",
                "name" => "Kalihati",
            ],
            [
                "id" => "264",
                "city_id" => "17",
                "name" => "Nagarpur",
            ],
            [
                "id" => "265",
                "city_id" => "17",
                "name" => "Mirzapur",
            ],
            [
                "id" => "266",
                "city_id" => "17",
                "name" => "Gopalpur",
            ],
            [
                "id" => "267",
                "city_id" => "17",
                "name" => "Delduar",
            ],
            [
                "id" => "268",
                "city_id" => "17",
                "name" => "Bhuapur",
            ],
            [
                "id" => "269",
                "city_id" => "17",
                "name" => "Dhanbari",
            ],
            [
                "id" => "270",
                "city_id" => "55",
                "name" => "Bagerhat Sadar",
            ],
            [
                "id" => "271",
                "city_id" => "55",
                "name" => "Chitalmari",
            ],
            [
                "id" => "272",
                "city_id" => "55",
                "name" => "Fakirhat",
            ],
            [
                "id" => "273",
                "city_id" => "55",
                "name" => "Kachua",
            ],
            [
                "id" => "274",
                "city_id" => "55",
                "name" => "Mollahat",
            ],
            [
                "id" => "275",
                "city_id" => "55",
                "name" => "Mongla",
            ],
            [
                "id" => "276",
                "city_id" => "55",
                "name" => "Morrelganj",
            ],
            [
                "id" => "277",
                "city_id" => "55",
                "name" => "Rampal",
            ],
            [
                "id" => "278",
                "city_id" => "55",
                "name" => "Sarankhola",
            ],
            [
                "id" => "279",
                "city_id" => "56",
                "name" => "Damurhuda",
            ],
            [
                "id" => "280",
                "city_id" => "56",
                "name" => "Chuadanga-S",
            ],
            [
                "id" => "281",
                "city_id" => "56",
                "name" => "Jibannagar",
            ],
            [
                "id" => "282",
                "city_id" => "56",
                "name" => "Alamdanga",
            ],
            [
                "id" => "283",
                "city_id" => "57",
                "name" => "Abhaynagar",
            ],
            [
                "id" => "284",
                "city_id" => "57",
                "name" => "Keshabpur",
            ],
            [
                "id" => "285",
                "city_id" => "57",
                "name" => "Bagherpara",
            ],
            [
                "id" => "286",
                "city_id" => "57",
                "name" => "Jessore Sadar",
            ],
            [
                "id" => "287",
                "city_id" => "57",
                "name" => "Chaugachha",
            ],
            [
                "id" => "288",
                "city_id" => "57",
                "name" => "Manirampur",
            ],
            [
                "id" => "289",
                "city_id" => "57",
                "name" => "Jhikargachha",
            ],
            [
                "id" => "290",
                "city_id" => "57",
                "name" => "Sharsha",
            ],
            [
                "id" => "291",
                "city_id" => "58",
                "name" => "Jhenaidah Sadar",
            ],
            [
                "id" => "292",
                "city_id" => "58",
                "name" => "Maheshpur",
            ],
            [
                "id" => "293",
                "city_id" => "58",
                "name" => "Kaliganj",
            ],
            [
                "id" => "294",
                "city_id" => "58",
                "name" => "Kotchandpur",
            ],
            [
                "id" => "295",
                "city_id" => "58",
                "name" => "Shailkupa",
            ],
            [
                "id" => "296",
                "city_id" => "58",
                "name" => "Harinakunda",
            ],
            [
                "id" => "297",
                "city_id" => "59",
                "name" => "Terokhada",
            ],
            [
                "id" => "298",
                "city_id" => "59",
                "name" => "Batiaghata",
            ],
            [
                "id" => "299",
                "city_id" => "59",
                "name" => "Dacope",
            ],
            [
                "id" => "300",
                "city_id" => "59",
                "name" => "Dumuria",
            ],
            [
                "id" => "301",
                "city_id" => "59",
                "name" => "Dighalia",
            ],
            [
                "id" => "302",
                "city_id" => "59",
                "name" => "Koyra",
            ],
            [
                "id" => "303",
                "city_id" => "59",
                "name" => "Paikgachha",
            ],
            [
                "id" => "304",
                "city_id" => "59",
                "name" => "Phultala",
            ],
            [
                "id" => "305",
                "city_id" => "59",
                "name" => "Rupsa",
            ],
            [
                "id" => "306",
                "city_id" => "60",
                "name" => "Kushtia Sadar",
            ],
            [
                "id" => "307",
                "city_id" => "60",
                "name" => "Kumarkhali",
            ],
            [
                "id" => "308",
                "city_id" => "60",
                "name" => "Daulatpur",
            ],
            [
                "id" => "309",
                "city_id" => "60",
                "name" => "Mirpur",
            ],
            [
                "id" => "310",
                "city_id" => "60",
                "name" => "Bheramara",
            ],
            [
                "id" => "311",
                "city_id" => "60",
                "name" => "Khoksa",
            ],
            [
                "id" => "312",
                "city_id" => "61",
                "name" => "Magura Sadar",
            ],
            [
                "id" => "313",
                "city_id" => "61",
                "name" => "Mohammadpur",
            ],
            [
                "id" => "314",
                "city_id" => "61",
                "name" => "Shalikha",
            ],
            [
                "id" => "315",
                "city_id" => "61",
                "name" => "Sreepur",
            ],
            [
                "id" => "316",
                "city_id" => "62",
                "name" => "angni",
            ],
            [
                "id" => "317",
                "city_id" => "62",
                "name" => "Mujib Nagar",
            ],
            [
                "id" => "318",
                "city_id" => "62",
                "name" => "Meherpur-S",
            ],
            [
                "id" => "319",
                "city_id" => "63",
                "name" => "Narail-S Upazilla",
            ],
            [
                "id" => "320",
                "city_id" => "63",
                "name" => "Lohagara Upazilla",
            ],
            [
                "id" => "321",
                "city_id" => "63",
                "name" => "Kalia Upazilla",
            ],
            [
                "id" => "322",
                "city_id" => "64",
                "name" => "Satkhira Sadar",
            ],
            [
                "id" => "323",
                "city_id" => "64",
                "name" => "Assasuni",
            ],
            [
                "id" => "324",
                "city_id" => "64",
                "name" => "Debhata",
            ],
            [
                "id" => "325",
                "city_id" => "64",
                "name" => "Tala",
            ],
            [
                "id" => "326",
                "city_id" => "64",
                "name" => "Kalaroa",
            ],
            [
                "id" => "327",
                "city_id" => "64",
                "name" => "Kaliganj",
            ],
            [
                "id" => "328",
                "city_id" => "64",
                "name" => "Shyamnagar",
            ],
            [
                "id" => "329",
                "city_id" => "18",
                "name" => "Adamdighi",
            ],
            [
                "id" => "330",
                "city_id" => "18",
                "name" => "Bogra Sadar",
            ],
            [
                "id" => "331",
                "city_id" => "18",
                "name" => "Sherpur",
            ],
            [
                "id" => "332",
                "city_id" => "18",
                "name" => "Dhunat",
            ],
            [
                "id" => "333",
                "city_id" => "18",
                "name" => "Dhupchanchia",
            ],
            [
                "id" => "334",
                "city_id" => "18",
                "name" => "Gabtali",
            ],
            [
                "id" => "335",
                "city_id" => "18",
                "name" => "Kahaloo",
            ],
            [
                "id" => "336",
                "city_id" => "18",
                "name" => "Nandigram",
            ],
            [
                "id" => "337",
                "city_id" => "18",
                "name" => "Sahajanpur",
            ],
            [
                "id" => "338",
                "city_id" => "18",
                "name" => "Sariakandi",
            ],
            [
                "id" => "339",
                "city_id" => "18",
                "name" => "Shibganj",
            ],
            [
                "id" => "340",
                "city_id" => "18",
                "name" => "Sonatala",
            ],
            [
                "id" => "341",
                "city_id" => "19",
                "name" => "Joypurhat S",
            ],
            [
                "id" => "342",
                "city_id" => "19",
                "name" => "Akkelpur",
            ],
            [
                "id" => "343",
                "city_id" => "19",
                "name" => "Kalai",
            ],
            [
                "id" => "344",
                "city_id" => "19",
                "name" => "Khetlal",
            ],
            [
                "id" => "345",
                "city_id" => "19",
                "name" => "Panchbibi",
            ],
            [
                "id" => "346",
                "city_id" => "20",
                "name" => "Naogaon Sadar",
            ],
            [
                "id" => "347",
                "city_id" => "20",
                "name" => "Mohadevpur",
            ],
            [
                "id" => "348",
                "city_id" => "20",
                "name" => "Manda",
            ],
            [
                "id" => "349",
                "city_id" => "20",
                "name" => "Niamatpur",
            ],
            [
                "id" => "350",
                "city_id" => "20",
                "name" => "Atrai",
            ],
            [
                "id" => "351",
                "city_id" => "20",
                "name" => "Raninagar",
            ],
            [
                "id" => "352",
                "city_id" => "20",
                "name" => "Patnitala",
            ],
            [
                "id" => "353",
                "city_id" => "20",
                "name" => "Dhamoirhat",
            ],
            [
                "id" => "354",
                "city_id" => "20",
                "name" => "Sapahar",
            ],
            [
                "id" => "355",
                "city_id" => "20",
                "name" => "Porsha",
            ],
            [
                "id" => "356",
                "city_id" => "20",
                "name" => "Badalgachhi",
            ],
            [
                "id" => "357",
                "city_id" => "21",
                "name" => "Natore Sadar",
            ],
            [
                "id" => "358",
                "city_id" => "21",
                "name" => "Baraigram",
            ],
            [
                "id" => "359",
                "city_id" => "21",
                "name" => "Bagatipara",
            ],
            [
                "id" => "360",
                "city_id" => "21",
                "name" => "Lalpur",
            ],
            [
                "id" => "361",
                "city_id" => "21",
                "name" => "Natore Sadar",
            ],
            [
                "id" => "362",
                "city_id" => "21",
                "name" => "Baraigram",
            ],
            [
                "id" => "363",
                "city_id" => "22",
                "name" => "Bholahat",
            ],
            [
                "id" => "364",
                "city_id" => "22",
                "name" => "Gomastapur",
            ],
            [
                "id" => "365",
                "city_id" => "22",
                "name" => "Nachole",
            ],
            [
                "id" => "366",
                "city_id" => "22",
                "name" => "Nawabganj Sadar",
            ],
            [
                "id" => "367",
                "city_id" => "22",
                "name" => "Shibganj",
            ],
            [
                "id" => "368",
                "city_id" => "23",
                "name" => "Atgharia",
            ],
            [
                "id" => "369",
                "city_id" => "23",
                "name" => "Bera",
            ],
            [
                "id" => "370",
                "city_id" => "23",
                "name" => "Bhangura",
            ],
            [
                "id" => "371",
                "city_id" => "23",
                "name" => "Chatmohar",
            ],
            [
                "id" => "372",
                "city_id" => "23",
                "name" => "Faridpur",
            ],
            [
                "id" => "373",
                "city_id" => "23",
                "name" => "Ishwardi",
            ],
            [
                "id" => "374",
                "city_id" => "23",
                "name" => "Pabna Sadar",
            ],
            [
                "id" => "375",
                "city_id" => "23",
                "name" => "Santhia",
            ],
            [
                "id" => "376",
                "city_id" => "23",
                "name" => "Sujanagar",
            ],
            [
                "id" => "377",
                "city_id" => "24",
                "name" => "Bagha",
            ],
            [
                "id" => "378",
                "city_id" => "24",
                "name" => "Bagmara",
            ],
            [
                "id" => "379",
                "city_id" => "24",
                "name" => "Charghat",
            ],
            [
                "id" => "380",
                "city_id" => "24",
                "name" => "Durgapur",
            ],
            [
                "id" => "381",
                "city_id" => "24",
                "name" => "Godagari",
            ],
            [
                "id" => "382",
                "city_id" => "24",
                "name" => "Mohanpur",
            ],
            [
                "id" => "383",
                "city_id" => "24",
                "name" => "Paba",
            ],
            [
                "id" => "384",
                "city_id" => "24",
                "name" => "Puthia",
            ],
            [
                "id" => "385",
                "city_id" => "24",
                "name" => "Tanore",
            ],
            [
                "id" => "386",
                "city_id" => "25",
                "name" => "Sirajganj Sadar",
            ],
            [
                "id" => "387",
                "city_id" => "25",
                "name" => "Belkuchi",
            ],
            [
                "id" => "388",
                "city_id" => "25",
                "name" => "Chauhali",
            ],
            [
                "id" => "389",
                "city_id" => "25",
                "name" => "Kamarkhanda",
            ],
            [
                "id" => "390",
                "city_id" => "25",
                "name" => "Kazipur",
            ],
            [
                "id" => "391",
                "city_id" => "25",
                "name" => "Raiganj",
            ],
            [
                "id" => "392",
                "city_id" => "25",
                "name" => "Shahjadpur",
            ],
            [
                "id" => "393",
                "city_id" => "25",
                "name" => "Tarash",
            ],
            [
                "id" => "394",
                "city_id" => "25",
                "name" => "Ullahpara",
            ],
            [
                "id" => "395",
                "city_id" => "26",
                "name" => "Birampur",
            ],
            [
                "id" => "396",
                "city_id" => "26",
                "name" => "Birganj",
            ],
            [
                "id" => "397",
                "city_id" => "26",
                "name" => "Biral",
            ],
            [
                "id" => "398",
                "city_id" => "26",
                "name" => "Bochaganj",
            ],
            [
                "id" => "399",
                "city_id" => "26",
                "name" => "Chirirbandar",
            ],
            [
                "id" => "400",
                "city_id" => "26",
                "name" => "Phulbari",
            ],
            [
                "id" => "401",
                "city_id" => "26",
                "name" => "Ghoraghat",
            ],
            [
                "id" => "402",
                "city_id" => "26",
                "name" => "Hakimpur",
            ],
            [
                "id" => "403",
                "city_id" => "26",
                "name" => "Kaharole",
            ],
            [
                "id" => "404",
                "city_id" => "26",
                "name" => "Khansama",
            ],
            [
                "id" => "405",
                "city_id" => "26",
                "name" => "Dinajpur Sadar",
            ],
            [
                "id" => "406",
                "city_id" => "26",
                "name" => "Nawabganj",
            ],
            [
                "id" => "407",
                "city_id" => "26",
                "name" => "Parbatipur",
            ],
            [
                "id" => "408",
                "city_id" => "27",
                "name" => "Fulchhari",
            ],
            [
                "id" => "409",
                "city_id" => "27",
                "name" => "Gaibandha sadar",
            ],
            [
                "id" => "410",
                "city_id" => "27",
                "name" => "Gobindaganj",
            ],
            [
                "id" => "411",
                "city_id" => "27",
                "name" => "Palashbari",
            ],
            [
                "id" => "412",
                "city_id" => "27",
                "name" => "Sadullapur",
            ],
            [
                "id" => "413",
                "city_id" => "27",
                "name" => "Saghata",
            ],
            [
                "id" => "414",
                "city_id" => "27",
                "name" => "Sundarganj",
            ],
            [
                "id" => "415",
                "city_id" => "28",
                "name" => "Kurigram Sadar",
            ],
            [
                "id" => "416",
                "city_id" => "28",
                "name" => "Nageshwari",
            ],
            [
                "id" => "417",
                "city_id" => "28",
                "name" => "Bhurungamari",
            ],
            [
                "id" => "418",
                "city_id" => "28",
                "name" => "Phulbari",
            ],
            [
                "id" => "419",
                "city_id" => "28",
                "name" => "Rajarhat",
            ],
            [
                "id" => "420",
                "city_id" => "28",
                "name" => "Ulipur",
            ],
            [
                "id" => "421",
                "city_id" => "28",
                "name" => "Chilmari",
            ],
            [
                "id" => "422",
                "city_id" => "28",
                "name" => "Rowmari",
            ],
            [
                "id" => "423",
                "city_id" => "28",
                "name" => "Char Rajibpur",
            ],
            [
                "id" => "424",
                "city_id" => "29",
                "name" => "Lalmanirhat Sadar",
            ],
            [
                "id" => "425",
                "city_id" => "29",
                "name" => "Aditmari",
            ],
            [
                "id" => "426",
                "city_id" => "29",
                "name" => "Kaliganj",
            ],
            [
                "id" => "427",
                "city_id" => "29",
                "name" => "Hatibandha",
            ],
            [
                "id" => "428",
                "city_id" => "29",
                "name" => "Patgram",
            ],
            [
                "id" => "429",
                "city_id" => "30",
                "name" => "Nilphamari Sadar",
            ],
            [
                "id" => "430",
                "city_id" => "30",
                "name" => "Saidpur",
            ],
            [
                "id" => "431",
                "city_id" => "30",
                "name" => "Jaldhaka",
            ],
            [
                "id" => "432",
                "city_id" => "30",
                "name" => "Kishoreganj",
            ],
            [
                "id" => "433",
                "city_id" => "30",
                "name" => "Domar",
            ],
            [
                "id" => "434",
                "city_id" => "30",
                "name" => "Dimla",
            ],
            [
                "id" => "435",
                "city_id" => "31",
                "name" => "Panchagarh Sadar",
            ],
            [
                "id" => "436",
                "city_id" => "31",
                "name" => "Debiganj",
            ],
            [
                "id" => "437",
                "city_id" => "31",
                "name" => "Boda",
            ],
            [
                "id" => "438",
                "city_id" => "31",
                "name" => "Atwari",
            ],
            [
                "id" => "439",
                "city_id" => "31",
                "name" => "Tetulia",
            ],
            [
                "id" => "440",
                "city_id" => "32",
                "name" => "Badarganj",
            ],
            [
                "id" => "441",
                "city_id" => "32",
                "name" => "Mithapukur",
            ],
            [
                "id" => "442",
                "city_id" => "32",
                "name" => "Gangachara",
            ],
            [
                "id" => "443",
                "city_id" => "32",
                "name" => "Kaunia",
            ],
            [
                "id" => "444",
                "city_id" => "32",
                "name" => "Rangpur Sadar",
            ],
            [
                "id" => "445",
                "city_id" => "32",
                "name" => "Pirgachha",
            ],
            [
                "id" => "446",
                "city_id" => "32",
                "name" => "Pirganj",
            ],
            [
                "id" => "447",
                "city_id" => "32",
                "name" => "Taraganj",
            ],
            [
                "id" => "448",
                "city_id" => "33",
                "name" => "Thakurgaon Sadar",
            ],
            [
                "id" => "449",
                "city_id" => "33",
                "name" => "Pirganj",
            ],
            [
                "id" => "450",
                "city_id" => "33",
                "name" => "Baliadangi",
            ],
            [
                "id" => "451",
                "city_id" => "33",
                "name" => "Haripur",
            ],
            [
                "id" => "452",
                "city_id" => "33",
                "name" => "Ranisankail",
            ],
            [
                "id" => "453",
                "city_id" => "51",
                "name" => "Ajmiriganj",
            ],
            [
                "id" => "454",
                "city_id" => "51",
                "name" => "Baniachang",
            ],
            [
                "id" => "455",
                "city_id" => "51",
                "name" => "Bahubal",
            ],
            [
                "id" => "456",
                "city_id" => "51",
                "name" => "Chunarughat",
            ],
            [
                "id" => "457",
                "city_id" => "51",
                "name" => "Habiganj Sadar",
            ],
            [
                "id" => "458",
                "city_id" => "51",
                "name" => "Lakhai",
            ],
            [
                "id" => "459",
                "city_id" => "51",
                "name" => "Madhabpur",
            ],
            [
                "id" => "460",
                "city_id" => "51",
                "name" => "Nabiganj",
            ],
            [
                "id" => "461",
                "city_id" => "51",
                "name" => "Shaistagonj",
            ],
            [
                "id" => "462",
                "city_id" => "52",
                "name" => "Moulvibazar Sadar",
            ],
            [
                "id" => "463",
                "city_id" => "52",
                "name" => "Barlekha",
            ],
            [
                "id" => "464",
                "city_id" => "52",
                "name" => "Juri",
            ],
            [
                "id" => "465",
                "city_id" => "52",
                "name" => "Kamalganj",
            ],
            [
                "id" => "466",
                "city_id" => "52",
                "name" => "Kulaura",
            ],
            [
                "id" => "467",
                "city_id" => "52",
                "name" => "Rajnagar",
            ],
            [
                "id" => "468",
                "city_id" => "52",
                "name" => "Sreemangal",
            ],
            [
                "id" => "469",
                "city_id" => "53",
                "name" => "Bishwamvarpur",
            ],
            [
                "id" => "470",
                "city_id" => "53",
                "name" => "Chhatak",
            ],
            [
                "id" => "471",
                "city_id" => "53",
                "name" => "Derai",
            ],
            [
                "id" => "472",
                "city_id" => "53",
                "name" => "Dharampasha",
            ],
            [
                "id" => "473",
                "city_id" => "53",
                "name" => "Dowarabazar",
            ],
            [
                "id" => "474",
                "city_id" => "53",
                "name" => "Jagannathpur",
            ],
            [
                "id" => "475",
                "city_id" => "53",
                "name" => "Jamalganj",
            ],
            [
                "id" => "476",
                "city_id" => "53",
                "name" => "Sulla",
            ],
            [
                "id" => "477",
                "city_id" => "53",
                "name" => "Sunamganj Sadar",
            ],
            [
                "id" => "478",
                "city_id" => "53",
                "name" => "Shanthiganj",
            ],
            [
                "id" => "479",
                "city_id" => "53",
                "name" => "Tahirpur",
            ],
            [
                "id" => "480",
                "city_id" => "54",
                "name" => "Sylhet Sadar",
            ],
            [
                "id" => "481",
                "city_id" => "54",
                "name" => "Beanibazar",
            ],
            [
                "id" => "482",
                "city_id" => "54",
                "name" => "Bishwanath",
            ],
            [
                "id" => "483",
                "city_id" => "54",
                "name" => "Dakshin Surma",
            ],
            [
                "id" => "484",
                "city_id" => "54",
                "name" => "Balaganj",
            ],
            [
                "id" => "485",
                "city_id" => "54",
                "name" => "Companiganj",
            ],
            [
                "id" => "486",
                "city_id" => "54",
                "name" => "Fenchuganj",
            ],
            [
                "id" => "487",
                "city_id" => "54",
                "name" => "Golapganj",
            ],
            [
                "id" => "488",
                "city_id" => "54",
                "name" => "Gowainghat",
            ],
            [
                "id" => "489",
                "city_id" => "54",
                "name" => "Jointapur",
            ],
            [
                "id" => "490",
                "city_id" => "54",
                "name" => "Kanaighat",
            ],
            [
                "id" => "491",
                "city_id" => "54",
                "name" => "Zakiganj",
            ],
            [
                "id" => "492",
                "city_id" => "54",
                "name" => "Nobigonj",
            ],
            [
                "id" => "493",
                "city_id" => "45",
                "name" => "Eidgaon",
            ],
            [
                "id" => "494",
                "city_id" => "53",
                "name" => "Modhyanagar",
            ],
            [
                "id" => "495",
                "city_id" => "7",
                "name" => "Dasar",
            ],
            [
                'id' => '496',
                'city_id' => '65',
                'name' => 'Agargaon'
            ],
            [
                'id' => '497',
                'city_id' => '65',
                'name' => 'Badda'
            ],
            [
                'id' => '498',
                'city_id' => '65',
                'name' => 'Bashundhara R/A'
            ],
            [
                'id' => '499',
                'city_id' => '65',
                'name' => 'Biman Bandar Thana'
            ],
            [
                'id' => '500',
                'city_id' => '65',
                'name' => 'Cantonment'
            ],
            [
                'id' => '501',
                'city_id' => '65',
                'name' => 'Demra'
            ],
            [
                'id' => '502',
                'city_id' => '65',
                'name' => 'Dhanmondi'
            ],
            [
                'id' => '503',
                'city_id' => '65',
                'name' => 'Dohar'
            ],
            [
                'id' => '504',
                'city_id' => '65',
                'name' => 'Gulshan'
            ],
            [
                'id' => '505',
                'city_id' => '65',
                'name' => 'Hazaribagh'
            ], [
                'id' => '506',
                'city_id' => '65',
                'name' => 'Kafrul'
            ],
            [
                'id' => '507',
                'city_id' => '65',
                'name' => 'Kamrangir Char'
            ],
            [
                'id' => '508',
                'city_id' => '65',
                'name' => 'Keraniganj'
            ],
            [
                'id' => '509',
                'city_id' => '65',
                'name' => 'Khilgaon'
            ], [
                'id' => '510',
                'city_id' => '65',
                'name' => 'Kotwali'
            ],
            [
                'id' => '511',
                'city_id' => '65',
                'name' => 'Lalbagh'
            ],
            [
                'id' => '512',
                'city_id' => '65',
                'name' => 'Lalmatia'
            ],
            [
                'id' => '513',
                'city_id' => '65',
                'name' => 'Mirpur'
            ],
            [
                'id' => '514',
                'city_id' => '65',
                'name' => 'Mirpur'
            ],
            [
                'id' => '515',
                'city_id' => '65',
                'name' => 'Mohammadpur'
            ],
            [
                'id' => '516',
                'city_id' => '65',
                'name' => 'Motijheel'
            ],
            [
                'id' => '517',
                'city_id' => '65',
                'name' => 'Nabinagor'
            ],
            [
                'id' => '518',
                'city_id' => '65',
                'name' => 'Nikunja'
            ],
            [
                'id' => '519',
                'city_id' => '65',
                'name' => 'Pallabi'
            ],
            [
                'id' => '520',
                'city_id' => '65',
                'name' => 'Ramna'
            ],
            [
                'id' => '521',
                'city_id' => '65',
                'name' => 'Sabujbagh'
            ],
            [
                'id' => '522',
                'city_id' => '65',
                'name' => 'Savar'
            ],
            [
                'id' => '523',
                'city_id' => '65',
                'name' => 'Shutrapur'
            ],
            [
                'id' => '524',
                'city_id' => '65',
                'name' => 'Shyampur'
            ],
            [
                'id' => '525',
                'city_id' => '65',
                'name' => 'Tejgaon'
            ],
            [
                'id' => '526',
                'city_id' => '65',
                'name' => 'Uttara'
            ],

        ];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('areas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('areas')->insert($areas);
    }
}
